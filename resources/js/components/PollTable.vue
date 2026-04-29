<script setup>
import { ref } from 'vue';
import { useFetchApi } from '../composables/useFetchApi';
const copied = ref(null);
import PollEdit from './PollEdit.vue';

  const props = defineProps({
    polls: { type: Array, default: () => [] },
  });

  //pr le PollEdit.vue
  const editingPoll = ref(null);

  const { fetchApi } = useFetchApi();
  //loadingId me permet de suivre l'ID du sondage supprimé.
  const loadingId = ref(null);

  //fonction pr que je suppr le sondage
  const deletePoll = async (pollId) => {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce sondage ?')) {
      return;
    }

    loadingId.value = pollId;

    try {
      await fetchApi({
        url: `/polls/${pollId}`,
        method: 'DELETE',
      });
      window.location.reload(); //me permet de refresh la page et d'afficher le tableau à jour
    } catch (err) {
      alert('Erreur: Impossible de supprimer ce sondage.');
      loadingId.value = null;
    }
  };
  
  //ma fonction pr le lien
  const copyLink = (secretToken) =>{
    navigator.clipboard.writeText(window.location.origin + '/vote/' + secretToken);
    copied.value = secretToken;
    // pr l'ui
    setTimeout(() => copied.value = null, 2000);
  }
</script>

<template>
  <div v-if="polls.length === 0"
    class="flex flex-col items-center justify-center py-16 text-gray-400">
    <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    </svg>
    <p class="text-sm">Aucun sondage pour le moment.</p>
  </div>

  <div v-else class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
    <table class="w-full text-sm text-left">

      <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
        <tr>
          <th class="px-4 py-3 font-medium">ID</th>
          <th class="px-4 py-3 font-medium">Titre</th>
          <th class="px-4 py-3 font-medium">Question</th>
          <th class="px-4 py-3 font-medium">Statut</th>
          <th class="px-4 py-3 font-medium">Début</th>
          <th class="px-4 py-3 font-medium">Fin</th>
          <th class="px-4 py-3 font-medium">Actions</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-100 bg-white">
        <tr v-for="poll in polls" :key="poll.id"
          class="transition-colors hover:bg-gray-50">

          <td class="px-4 py-3 text-gray-400 font-mono text-xs">#{{ poll.id }}</td>

          <td class="px-4 py-3 font-medium text-gray-800">
            {{ poll.title || '—' }}
          </td>

          <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
            {{ poll.question }}
          </td>

          <td class="px-4 py-3">
            <span v-if="poll.is_draft"
              class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
              Brouillon
            </span>
            <span v-else
              class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
              Lancé
            </span>
          </td>

          <td class="px-4 py-3 text-gray-500 text-xs">
            {{ poll.started_at ?? '—' }}
          </td>

          <td class="px-4 py-3 text-gray-500 text-xs">
            {{ poll.ends_at ?? '—' }}
          </td>

          <td class="px-4 py-3">
            <button
              @click="editingPoll = poll"
              class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition-colors hover:bg-blue-100">
                Modifier
              </button>
            <button
              @click="deletePoll(poll.id)"
              :disabled="loadingId === poll.id"
              class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-600
                     transition-colors hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-40">
              {{ loadingId === poll.id ? 'En cours de supression' : 'Supprimer' }}
            </button>
            <button
            @click="copyLink(poll.secret_token)"
            class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-medium text-gray-600
                     transition-colors hover:bg-gray-300 disabled:cursor-not-allowed disabled:opacity-40">
              {{ copied === poll.secret_token ? 'Copié !' : 'Copier le lien' }}
            </button>
          </td>

        </tr>
      </tbody>

    </table>
  </div>
  <PollEdit v-if="editingPoll" :poll="editingPoll" />
</template>

<!-- <template>
  <p v-if="polls.length === 0">Aucun sondage.</p>

  <table v-else class="w-full border-collapse text-left">
    <thead>
      <tr>
        <th class="border px-3 py-2">ID</th>
        <th class="border px-3 py-2">Titre</th>
        <th class="border px-3 py-2">Question</th>
        <th class="border px-3 py-2">Brouillon</th>
        <th class="border px-3 py-2">Debut</th>
        <th class="border px-3 py-2">Fin</th>
        
      </tr>
    </thead>
    <tbody>
      <tr v-for="poll in polls" :key="poll.id">
        <td class="border px-3 py-2">{{ poll.id }}</td>
        <td class="border px-3 py-2">{{ poll.title || '-' }}</td>
        <td class="border px-3 py-2">{{ poll.question }}</td>
        <td class="border px-3 py-2">{{ poll.is_draft ? 'Oui' : 'Non' }}</td>
        <td class="border px-3 py-2">{{ poll.started_at || '-' }}</td>
        <td class="border px-3 py-2">{{ poll.ends_at || '-' }}</td>
        <td class="border px-3 py-2">
          <button 
            @click="deletePoll(poll.id)"
            :disabled="loadingId === poll.id"
            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 disabled:opacity-50 text-sm"
          >
            {{ loadingId === poll.id ? 'Suppression en cours' : 'Supprimer' }}
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template> -->
