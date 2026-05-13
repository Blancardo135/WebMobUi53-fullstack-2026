<script setup>
  import { watch, ref } from 'vue';
  import PollTable from './components/PollTable.vue';
  import PollCreate from './components/PollCreate.vue';
  import { useFetchApi } from './composables/useFetchApi';
  import { usePolling } from './composables/usePolling';
  import BaseButton from './components/BaseButton.vue';

  const props = defineProps({
    polls: { type: Array, default: () => [] },
    loginUrl: { type: String, default: null },
  });

  const showCreationForm = ref(false);

  const { fetchApiToRef } = useFetchApi();

  const { data: getResult, error: getError, fetchNow } = fetchApiToRef({ url: 'polls/' });
  const { data: postResult, error: postError } = fetchApiToRef({ url: '/foo', data: { id: 1 } });
watch(getResult, (val) => console.log(val));

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
    <BaseButton variant="blue" @click="showCreationForm = !showCreationForm">
      {{ showCreationForm ? 'Annuler' : 'Nouveau sondage' }}
    </BaseButton>

  <PollCreate v-if="showCreationForm"></PollCreate>
  <PollTable v-else :polls="getResult ?? props.polls" />
  </main>
</template>

