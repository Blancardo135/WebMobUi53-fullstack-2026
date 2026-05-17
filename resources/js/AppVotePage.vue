<script setup>
import { ref, computed } from 'vue';
import { useFetchApi } from './composables/useFetchApi';
import PollVote from './components/PollVote.vue';
import PollResults from './components/PollResults.vue';


const props = defineProps({
  token: { type: String, required: true },
  loginUrl: { type: String, default: null },
});

const { fetchApiToRef } = useFetchApi();

const { data: poll, error, loading } = fetchApiToRef({
  url: `/polls/${props.token}`,
});

const hasVoted = ref(false);

//puisque isExpired est un boolean, on retourne t ou f 
const isExpired = computed(() => {
  if (!poll.value?.ends_at) return false;
  return new Date() > new Date(poll.value.ends_at);
});

</script>
<template>

  <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
    <nav>
      <a href="/polls/dashboard"
        class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-medium text-gray-600 transition-colors hover:bg-gray-100">
        Retourner au dashboard
      </a>
    </nav>
  </header>

  <main class="min-h-screen px-4 py-6 max-w-2xl mx-auto">

    <div v-if="loading" class="text-gray-400 text-sm">Chargement...</div>

    <div v-else-if="error" class="text-red-500 text-sm">
      {{ error.status === 404 ? 'Sondage introuvable ou lien invalide.' : 'Vous n\'avez pas accès à ce sondage.' }}
    </div>


    <div v-else-if="poll">
      <div v-if="isExpired"
        class="rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-700 mb-4">
        Ce sondage est terminé — il n'est plus possible de voter.
      </div>

      <PollVote v-if="!isExpired" :poll="poll" @voted="hasVoted = true" />
      <!-- me permet de bien afficher les résultats dans des cas précis -->
      <PollResults v-if="poll.results_public || poll.is_owner" :token="token" />
      <div v-else-if="hasVoted && !poll.results_public && !poll.is_owner"
        class="mt-8 rounded-lg bg-gray-50 border border-gray-200 px-4 py-3 text-sm text-gray-500">
        Votre vote a été enregistré. Les résultats sont privés.
      </div>
    </div>
  </main>
</template>