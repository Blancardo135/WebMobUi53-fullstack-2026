<script setup>
import { ref } from 'vue';
import { useFetchApi } from '../composables/useFetchApi';
import PollForm from './PollForm.vue';
import BaseModal from './BaseModal.vue';
import { useRoute } from '../stores/route';
const {showPollsTable} = useRoute();


const {fetchApi} = useFetchApi();
//recoit en props depuis PollTable et initialise les ref.
const props = defineProps({
    poll: {
        type: Object,
        required : true
    },
    fetchNow: {
      type: Function,
      default: null
    }
})

//c'est le seul moyen que j'ai trouvé pour m'assurer que ça fonctionne bien, en déstructurant.
const fnFetchNow = props.fetchNow;

//conversion des booleans puisque je veux true false et pas 0 ou 1
const title = ref(props.poll.title);
const question = ref(props.poll.question);
const options = ref(props.poll.options.map(o => ({ label: o.label })));
const isDraft = ref(!!props.poll.is_draft);
const allowMultipleChoices = ref(!!props.poll.allow_multiple_choices);
const allowVoteChange = ref(!!props.poll.allow_vote_change);
const resultsPublic = ref(!!props.poll.results_public);
const duration = ref(props.poll.duration ? props.poll.duration / 86400 : null);
const showWarning = ref(false);

const loading = ref(false);
const error = ref(null);

const editPoll = async()=>{
loading.value = true;
error.value = null;

try{
    // const res = await.. 
    const res = await fetchApi({
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
    if(res){
      if (fnFetchNow) fnFetchNow(); //sinon le chargement ne fonctionnait pas
       loading.value = false;
      showPollsTable();
    }
    //window.location.reload();
    // verif success ou pas et donc modifier si succes
    //mettre ensuite navigateTo(dashboard) si succes et sinon dire erreur
}catch (err){
  console.log('erreur catch', err);
    error.value = 'Erreur lors de la modif';
    loading.value = false;
}
}
//me permet de gérer la modale pr la soumission brouillon
const handleSubmit = () => {
  if (!isDraft.value) {
    showWarning.value = true;
  } else {
    editPoll();
  }
};

const onConfirmed = () => {
  showWarning.value = false;
  editPoll();
};
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
    @submit="handleSubmit"
  />

  <BaseModal
    v-if="showWarning"
    title="Lancer ce sondage ?"
    message="Une fois lancé, ce sondage ne pourra plus être modifié ni repassé en brouillon."
    confirmLabel="Lancer"
    confirmVariant="blue"
    @confirm="onConfirmed"
    @cancel="showWarning = false"
  />
</template>