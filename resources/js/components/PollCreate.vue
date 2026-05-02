<script setup>
import { ref } from 'vue';
import PollForm from './PollForm.vue';
import {useFetchApi} from '../composables/useFetchApi';

const {fetchApi} = useFetchApi();

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

const createPoll = async()=>{
    loading.value = true;
    error.value = null;
    try{
        await fetchApi({
        url:'/polls',
        method:'POST',
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
    window.location.reload();
} catch(err) {
error.value = 'Erreur de création';
loading.value = false;
}
};

</script>

<template>
    <!-- ici v-model passe la valeur des ref parent à l'enfant et écoute l'émission de l'enfant pour mettre à jour la valeur ici -->
  <PollForm
    v-model:title="title"
    v-model:question="question"
    v-model:options="options"
    v-model:isDraft="isDraft"
    v-model:allowMultipleChoices="allowMultipleChoices"
    v-model:allowVoteChange="allowVoteChange"
    v-model:resultsPublic="resultsPublic"
    v-model:duration="duration"
    :loading="loading"
    :error="error"
    submit-label="Créer le sondage"
    @submit="createPoll"
  />
</template>