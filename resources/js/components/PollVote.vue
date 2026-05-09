<script setup>
import { voteStore } from '../stores/voteStore';
//pr l'ajout des résultats
const emit = defineEmits(['voted']);

//récupère cela depuis AppVotePage qui lui s'occupe du fetch
const props = defineProps({
    poll: {
        type: Object,
        required: true
    },
});

//voteStore va gérer ce que je lui done
const { selectedOptionIds, loading, error, success, changeOption, submitVote } = voteStore(props.poll)

</script>

<template>
<div>
    <div class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-800">{{ poll.title}}</h1>
      <p class="text-gray-500 mt-1">{{ poll.question }}</p>
    </div>

    <div v-if="success"
      class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
      Vote enregistré avec succès !
    </div>

    <div v-else>

      <span class="text-xs uppercase tracking-wide font-medium text-gray-400">Options</span>

      <ul class="space-y-2 mt-3">
        <li v-for="option in poll.options" :key="option.id">
          <button
            @click="changeOption(option.id)"
            :class="[
                // check avc include que loption se trouve deja selectionée
              'w-full text-left rounded-xl border px-4 py-3 text-sm transition-colors shadow-sm',
              selectedOptionIds.includes(option.id)
                ? 'bg-blue-50 border-blue-400 text-blue-700'
                : 'bg-white border-gray-200 text-gray-700 hover:bg-blue-50 hover:border-blue-300'
            ]"
          >
            {{ option.label }}
          </button>
        </li>
      </ul>

      <p v-if="error" class="mt-3 text-sm text-red-500">{{ error }}</p>

      <button
        @click="submitVote(()=> emit('voted'))"
        :disabled="loading || selectedOptionIds.length === 0"
        class="mt-4 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
      >
        {{ loading ? 'En cours...' : 'Voter' }}
      </button>

    
</div>
</div>

</template>