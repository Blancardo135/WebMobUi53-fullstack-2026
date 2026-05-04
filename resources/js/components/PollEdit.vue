<script setup>
import { ref } from 'vue';
import { useFetchApi } from '../composables/useFetchApi';
import PollForm from './PollForm.vue';


const {fetchApi} = useFetchApi();
const props = defineProps({
    poll: {
        type: Object,
        required : true
    }
})
const title = ref(props.poll.title);
const question = ref(props.poll.question);
const options = ref(props.poll.options.map(o => ({ label: o.label })));
const isDraft = ref(props.poll.is_draft);
const allowMultipleChoices = ref(props.poll.allow_multiple_choices);
const allowVoteChange = ref(props.poll.allow_vote_change);
const resultsPublic = ref(props.poll.results_public);
const duration = ref(props.poll.duration);

const loading = ref(false);
const error = ref(null);

const editPoll = async()=>{
loading.value = true;
error.value = null;
try{
    await fetchApi({
        url: `/polls/${props.poll.id}`,
        method: 'PUT',
        data:{
            question: question.value,
            options: options.value,
            is_draft: isDraft.value,
            allow_multiple_choices: allowMultipleChoices.value,
            allow_vote_change: allowVoteChange.value,
            results_public: resultsPublic.value,
            duration: duration.value,
            title: title.value,
        },

    });
    window.location.reload();
}catch (err){
    error.value = 'Erreur lors de la modif';
    loading.value = false;
}
}
</script>

<template>
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
    submitLabel="Modifier le sondage"
    @submit="editPoll"
  />
</template>