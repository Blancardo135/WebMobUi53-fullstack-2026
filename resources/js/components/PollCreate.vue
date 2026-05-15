<script setup>
import { ref } from 'vue';
import PollForm from './PollForm.vue';
import { useFetchApi } from '../composables/useFetchApi';
import BaseModal from './BaseModal.vue';
import { useRoute } from '../stores/route.js';
const { showPollsTable } = useRoute();

const { fetchApi } = useFetchApi();

const props = defineProps({
  fetchNow: { type: Function, default: null },
});

const title = ref('');
const question = ref('');
const options = ref([{ label: '' }, { label: '' }]);
const isDraft = ref(true);
const allowMultipleChoices = ref(false);
const allowVoteChange = ref(false);
const resultsPublic = ref(false);
const duration = ref(null);
const loading = ref(false);
const error = ref(null);
const showWarning = ref(false);


const createPoll = async () => {
  loading.value = true;
  error.value = null;
  try {
    const res = await fetchApi({
      url: '/polls',
      method: 'POST',
      data: {
        title: title.value,
        question: question.value,
        options: options.value,
        is_draft: isDraft.value,
        allow_multiple_choices: allowMultipleChoices.value,
        allow_vote_change: allowVoteChange.value,
        results_public: resultsPublic.value,
        duration: duration.value,
      },
    });
    if (res) {
      loading.value = false;
      props.fetchNow();
      showPollsTable();
    }
  } catch (err) {
    if (err.status === 422) {
      // le 422 me permet de check si erreur validation ou serveur
      error.value = 'Veuillez remplir tous les champs obligatoires (titre, question et options).';
    } else {
      error.value = err.data?.message ?? 'Erreur de création.';
    }
    loading.value = false;
  }
};

//me permet de gérer la modale pr la soumission brouillon
const handleSubmit = () => {
  if (!isDraft.value) {
    showWarning.value = true;
  } else {
    createPoll();
  }
};

const onConfirmed = () => {
  showWarning.value = false;
  createPoll();
};

</script>

<template>

  <PollForm v-model:title="title" v-model:question="question" v-model:options="options" v-model:isDraft="isDraft"
    v-model:allowMultipleChoices="allowMultipleChoices" v-model:allowVoteChange="allowVoteChange"
    v-model:resultsPublic="resultsPublic" v-model:duration="duration" :loading="loading" :error="error"
    submitLabel="Créer le sondage" @submit="handleSubmit" />

  <BaseModal v-if="showWarning" title="Lancer ce sondage ?"
    message="Une fois lancé, ce sondage ne pourra plus être modifié ni repassé en brouillon." confirmLabel="Lancer"
    confirmVariant="blue" @confirm="onConfirmed" @cancel="showWarning = false" />
</template>