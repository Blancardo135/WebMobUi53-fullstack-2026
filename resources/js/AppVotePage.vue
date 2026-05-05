<script setup>
import { ref, computed } from 'vue';
import { useFetchApi } from './composables/useFetchApi';
import PollVote from './components/PollVote.vue';
import PollResults from './components/PollResults.vue';
import { voteStore } from './stores/voteStore';

const props = defineProps({
    token: {type: String, required: true},
    loginUrl: { type: String, default: null},
});

const {fetchApiToRef} = useFetchApi();

const {data: poll, error, loading} = fetchApiToRef({
    url: `/polls/${props.token}`,
});

//me permet de gérer les résultats
// const {success} = voteStore(poll.value);
const hasVoted = ref(false);

const showResults = computed(()=>{
  return poll.value?.results_public || success.value
})

</script>

<template>
  <main class="min-h-screen p-6 max-w-2xl mx-auto">

    <div v-if="loading" class="text-gray-400 text-sm">Chargement...</div>

    <div v-else-if="error" class="text-red-500 text-sm">
      Sondage introuvable ou lien invalide.
    </div>

    <div v-else-if="poll">
    <PollVote :poll="poll" />
    <PollResults v-if="poll.results_public || hasVoted" :token="token" />
    </div>

  </main>
</template>