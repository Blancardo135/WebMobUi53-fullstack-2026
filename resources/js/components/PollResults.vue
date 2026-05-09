<script setup>
import { computed } from 'vue';
import { useFetchApi } from '../composables/useFetchApi';
import {usePolling} from '../composables/usePolling';
import { voteStore } from '../stores/voteStore';

//le token vient de AppVote
const props = defineProps({
    token:{
        type: String,
        required: true
    },
});

const {fetchApiToRef} = useFetchApi();
const {data: results, loading, error, fetchNow} = fetchApiToRef({
    url:`/polls/${props.token}/results`,
});

usePolling(fetchNow, 3000);

//computed me permet de recalculer lorsque ça change
const options = computed(()=> results.value?.options ?? []);
const totalVotes = computed(()=> results.value?.total_votes ?? 0);

const percentage = (votesCount)=>{
    if(totalVotes.value === 0) return 0;
    return Math.round((votesCount/totalVotes.value)*100);
}
</script>


<template>
  <div class="mt-8">
    <div class="mb-3">
      <span class="text-xs uppercase tracking-wide font-medium text-gray-400">Résultats</span>
      <span class="ml-2 text-xs text-gray-400">{{ totalVotes }} vote{{ totalVotes > 1 ? 's' : '' }}</span>
    </div>

    <div v-if="loading && !results" class="text-sm text-gray-400">Chargement...</div>

    <div v-else class="space-y-3">
      <div v-for="option in options" :key="option.id">
        <div class="flex justify-between text-sm mb-1">
          <span class="text-gray-700">{{ option.label }}</span>
          <span class="text-gray-500">{{ option.votes_count }} ({{ percentage(option.votes_count) }}%)</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-2.5">
          <div
            class="bg-blue-500 h-2.5 rounded-full transition-all duration-500"
            :style="{ width: `${percentage(option.votes_count)}%` }"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>