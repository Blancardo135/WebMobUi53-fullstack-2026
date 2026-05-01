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
<!-- titre -->
 <div>
 <label for="">Title</label>
 <input
    :value="title"
    @input="emit('update:title'), $event.target.value"
    type="text"
    placeholder="Titre du sondage (Optionnel)"
 />

 <!-- question -->
  <div>
    <label>Question</label>
    <input
    :value="question"
    @input="emit('update:question', $event.target.value)"
    type="text"
    placeholder="Écrivez votre quiestion : "
    />
  </div>
  
  <div>
    <label for="">Options</label>
    <div v-for="(option, index) in options" :key="index"></div>
    <input
    :value="option.label"
    @input="updateOptionLabel(index, $event.target.value)"
    type="text"
    :placeholder= "`Options ${index+1}` "
    />
    <button
    v-if="options.length > 2"
    @click="removeOption(index)"
    >Supprimer cette option</button>
  </div>
  <button
  @click="addOption"
  >Ajouter une option</button>
 </div>

</template>