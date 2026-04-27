<script setup>
import { ref } from 'vue';
import { useFetchApi } from '../composables/useFetchApi';

  const props = defineProps({
    polls: { type: Array, default: () => [] },
  });

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
</script>

<template>
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
        <th class="border px-3 py-2">Action</th>
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
</template>
