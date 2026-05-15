<script setup>
  import { watch, ref } from 'vue';
  import PollTable from './components/PollTable.vue';
  import PollCreate from './components/PollCreate.vue';
  import { useFetchApi } from './composables/useFetchApi';
  import { usePolling } from './composables/usePolling';
  import BaseButton from './components/BaseButton.vue';
  import { useRoute } from './stores/route.js';

  const props = defineProps({
    polls: { type: Array, default: () => [] },
    loginUrl: { type: String, default: null },
  });

  const { fetchApiToRef } = useFetchApi();

  const { currentView, showCreateForm, showPollsTable } = useRoute();
  const { data: getResult, error: getError, fetchNow } = fetchApiToRef({ url: 'polls/' });
  // const { data: postResult, error: postError } = fetchApiToRef({ url: '/foo', data: { id: 1 } });


  function handleError(err) {
    if (!err) return;
    if (err?.status === 401) {
      window.location.href = props.loginUrl;
    } else {
      console.error(err);
    }
  }

  watch(getError, err => handleError(err));
  // watch(postError, handleError);

  usePolling(fetchNow);
</script>

<template>
  <main class="min-h-screen p-6">
    <h1 class="mb-4 text-xl font-semibold">Mes sondages</h1>
    <BaseButton
      variant="blue"
      class="mb-4" 
      @click="currentView === 'create' ? showPollsTable() : showCreateForm()">
      {{ currentView === 'create' ? 'Annuler' : 'Nouveau sondage' }}
    </BaseButton>

  <PollCreate v-if="currentView === 'create'" :fetchNow="fetchNow" />
  <PollTable v-else :polls="getResult ?? props.polls" :fetchNow="fetchNow" />
  <!-- getResult est la liste de sondage, qui met le ref "data" à jour -->
  </main>
</template>

