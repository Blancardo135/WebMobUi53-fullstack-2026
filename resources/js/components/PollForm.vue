<script setup>
const props = defineProps({
  title: { type: String, default: '' },
  question: { type: String, default: '' },
  options: { type: Array, default: () => [{ label: '' }, { label: '' }] },
  isDraft: { type: Boolean, default: true },
  allowMultipleChoices: { type: Boolean, default: false },
  allowVoteChange: { type: Boolean, default: false },
  resultsPublic: { type: Boolean, default: false },
  duration: { type: Number, default: null },
  loading: { type: Boolean, default: false },
  error: { type: String, default: null },
  submitLabel: { type: String, default: 'Enregistrer' },
});

const emit = defineEmits([
  'update:title',
  'update:question',
  'update:options',
  'update:isDraft',
  'update:allowMultipleChoices',
  'update:allowVoteChange',
  'update:resultsPublic',
  'update:duration',
  'submit',
]);

//ici update:options me permet d'envoyer le tableau modifié au parnent
const addOption = ()=>{
    emit('update:options', [...props.options, {label: ''}]);
};

const removeOption = (index) =>{
    const updated = [...props.options];
    updated.splice(index, 1);
    emit('uptade:options', updated);
};

const updateOptionLabel = (index, value)=>{
    const updated = [...props.options.label];
    //remplace  l'option à la position index avec une copie de la valeur mise à jour
    updated[index] = {...updated[index], label: value};
    emit('update:options', updated)
}


</script>

<template>

</template>