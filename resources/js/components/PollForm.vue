<script setup>
import BaseButton from './BaseButton.vue';

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
  submitLabel: { type: String, default: 'Soumettre' },
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
const addOption = () => {
  emit('update:options', [...props.options, { label: '' }]);
};

const removeOption = (index) => {
  const updated = [...props.options];
  updated.splice(index, 1);
  emit('update:options', updated);
};

const updateOptionLabel = (index, value) => {
  const updated = props.options.map((o, i) =>
    i === index ? { label: value } : { label: o.label }
  );
  emit('update:options', updated);
};


</script>

<template>
  <div class="max-w-lg space-y-4 w-full px-2">


    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
      <input :value="title" @input="emit('update:title', $event.target.value)" type="text"
        placeholder="Titre du sondage (Optionnel)"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>


    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
      <input :value="question" @input="emit('update:question', $event.target.value)" type="text"
        placeholder="Écrivez votre question"
        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>


    <div class="space-y-2">
      <label class="block text-sm font-medium text-gray-700">Options</label>
      <div v-for="(option, index) in options" :key="index" class="flex gap-2">
        <input :value="option.label" @input="updateOptionLabel(index, $event.target.value)" type="text"
          :placeholder="`Option ${index + 1}`"
          class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <BaseButton variant="red" v-if="options.length > 2" @click="removeOption(index)">
          Supprimer
        </BaseButton>
      </div>
      <BaseButton variant="gray" @click="addOption">
        Ajouter une option
      </BaseButton>
    </div>


    <div class="space-y-2 rounded-lg border border-gray-200 p-4">
      <p class="text-sm font-medium text-gray-700 mb-2">Paramètres</p>

      <div class="flex items-center gap-2">
        <input type="checkbox" id="isDraft" :checked="isDraft"
          @change="emit('update:isDraft', $event.target.checked)" />
        <label for="isDraft" class="text-sm text-gray-700">Brouillon</label>
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" id="allowMultipleChoices" :checked="allowMultipleChoices"
          @change="emit('update:allowMultipleChoices', $event.target.checked)" />
        <label for="allowMultipleChoices" class="text-sm text-gray-700">Choix multiples</label>
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" id="allowVoteChange" :checked="allowVoteChange"
          @change="emit('update:allowVoteChange', $event.target.checked)" />
        <label for="allowVoteChange" class="text-sm text-gray-700">Changement de vote autorisé</label>
      </div>

      <div class="flex items-center gap-2">
        <input type="checkbox" id="resultsPublic" :checked="resultsPublic"
          @change="emit('update:resultsPublic', $event.target.checked)" />
        <label for="resultsPublic" class="text-sm text-gray-700">Résultats publics</label>
      </div>

      <div class="flex items-center gap-2 pt-1">
        <input type="number" id="duration" :value="duration"
          @input="emit('update:duration', $event.target.value ? Number($event.target.value) : null)" min="1"
          class="w-24 rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <label for="duration" class="text-sm text-gray-700">Durée du sondage (jours)</label>
      </div>
    </div>

    <p v-if="error" class="text-sm text-red-500">{{ error }}</p>


    <BaseButton variant="blue" @click="emit('submit')" :disabled="loading">
      {{ loading ? 'En cours...' : submitLabel }}
    </BaseButton>

  </div>
</template>