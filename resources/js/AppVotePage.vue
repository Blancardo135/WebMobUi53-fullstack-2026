<script setup>
import { ref } from 'vue';
import { useFetchApi } from './composables/useFetchApi';
import PollVote from './components/PollVote.vue';

const props = defineProps({
    token: {type: String, required: true},
    loginUrl: { type: String, default: null},
});

const {fetchApiToRef} = useFetchApi();

const {data: poll, error, loading} = fetchApiToRef({
    url: `/polls/${props.token}`,
});

</script>

<template>
  <main class="min-h-screen p-6 max-w-2xl mx-auto">

    <div v-if="loading" class="text-gray-400 text-sm">Chargement...</div>

    <div v-else-if="error" class="text-red-500 text-sm">
      Sondage introuvable ou lien invalide.
    </div>

    <PollVote v-else-if="poll" :poll="poll" />

  </main>
</template>