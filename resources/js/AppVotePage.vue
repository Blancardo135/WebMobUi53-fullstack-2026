<script setup>
import { ref } from 'vue';
import { useFetchApi } from './composables/useFetchApi';

const props = defineProps({
    token: {type: String, required: true},
    loginUrl: { type: String, default: null},
});

const {fetchApiToRef} = useFetchApi();

const {data: poll, error, loading} = fetchApiToRef({
    url: `/polls/${props.token}`,
});

</script>

<template>
  <main class="min-h-screen p-6 max-w-2xl mx-auto">

    <div v-if="loading" class="text-gray-400 text-sm">Chargement...</div>

    <div v-else-if="error" class="text-red-500 text-sm">
      Sondage introuvable ou lien invalide.
    </div>

    <div v-else-if="poll">

      <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">{{ poll.title || poll.question }}</h1>
        <p v-if="poll.title" class="text-gray-500 mt-1">{{ poll.question }}</p>
      </div>

      <div class="mb-3">
        <span class="text-xs uppercase tracking-wide font-medium text-gray-400">Options</span>
      </div>

      <ul class="space-y-2">
        <li v-for="option in poll.options" :key="option.id">
          <button
            class="w-full text-left rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700
                   hover:bg-blue-50 hover:border-blue-300 transition-colors shadow-sm">
            {{ option.label }}
          </button>
        </li>
      </ul>

    </div>

  </main>
</template>