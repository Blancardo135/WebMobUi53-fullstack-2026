<script setup>
  import { watch, ref } from 'vue';
  import PollTable from './components/PollTable.vue';
  import PollCreate from './components/PollCreate.vue';
  import { useFetchApi } from './composables/useFetchApi';
  import { usePolling } from './composables/usePolling';

  const props = defineProps({
    polls: { type: Array, default: () => [] },
    loginUrl: { type: String, default: null },
  });

  const showCreationForm = ref(false);

  const { fetchApiToRef } = useFetchApi();

  const { data: getResult, error: getError, fetchNow } = fetchApiToRef({ url: 'polls/' });
  const { data: postResult, error: postError } = fetchApiToRef({ url: '/foo', data: { id: 1 } });

  function handleError(err) {
    if (!err) return;
    if (err?.status === 401) {
      window.location.href = props.loginUrl;
    } else {
      console.error(err);
    }
  }

  watch(getError, err => handleError(err));
  watch(postError, handleError);

  usePolling(fetchNow);
</script>

<template>
  <main class="min-h-screen p-6">
    <h1 class="mb-4 text-xl font-semibold">Mes sondages</h1>
    <button @click="showCreationForm = !showCreationForm" class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-black-600
                     transition-colors hover:bg-blue-100 disabled:cursor-not-allowed disabled:opacity-40">
    {{ showCreationForm ? 'Annuler' : 'Nouveau sondage' }}
    </button>

  <PollCreate v-if="showCreationForm"></PollCreate>
  <PollTable v-else :polls="props.polls" />
  </main>
</template>
