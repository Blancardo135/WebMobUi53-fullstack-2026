<script setup>
import { ref } from 'vue';
import { useFetchApi } from '../composables/useFetchApi';
const copied = ref(null);
import PollEdit from './PollEdit.vue';
import BaseModal from './BaseModal.vue';
import BaseButton from './BaseButton.vue';
import { useRoute } from '../stores/route.js';


  const props = defineProps({
    polls: { type: Array, default: () => [] },
    fetchNow: { type: Function, default: null },
  });

  const { currentView, editingPoll, showEditForm, showPollsTable } = useRoute();

  const pollToDelete = ref(null);

  const { fetchApi } = useFetchApi();
  //loadingId me permet de suivre l'ID du sondage supprimé.
  const loadingId = ref(null);

  //fonction pr que je suppr le sondage
  const deletePoll = async () => {
  loadingId.value = pollToDelete.value;
  try {
     await fetchApi({
      url: `/polls/${pollToDelete.value}`,
      method: 'DELETE',
    });
    
      if (props.fetchNow) props.fetchNow();
        showPollsTable();
    
  } catch (err) {
    alert('Erreur: Impossible de supprimer ce sondage.');
    loadingId.value = null;
  } finally {
    pollToDelete.value = null;
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
  <div v-if="editingPoll">
    <BaseButton variant="blue" @click="showPollsTable()" class="mb-4">
      ← Retour aux sondages
    </BaseButton>
    <PollEdit :poll="editingPoll" :fetchNow="props.fetchNow" />
  </div>

  <div v-else>
    <div v-if="polls.length === 0"
      class="flex flex-col items-center justify-center py-16 text-gray-400">
      <svg class="w-10 h-10 mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
      </svg>
      <p class="text-sm">Aucun sondage pour le moment.</p>
    </div>

    <div v-else>

      <!-- vue pr mobile  -->
      <div class="flex flex-col gap-3 md:hidden">
        <div v-for="poll in polls" :key="poll.id"
          class="rounded-xl border border-gray-200 bg-white shadow-sm p-4 space-y-3">

          <div class="flex items-start justify-between gap-2">
            <div>
              <p class="font-medium text-gray-800 text-sm">{{ poll.title || '—' }}</p>
              <p class="text-gray-500 text-xs mt-0.5 line-clamp-2">{{ poll.question }}</p>
            </div>
            <span v-if="poll.is_draft"
              class="shrink-0 inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">
              Brouillon
            </span>
            <span v-else
              class="shrink-0 inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">
              Lancé
            </span>
          </div>

          <div class="flex gap-4 text-xs text-gray-400">
            <span v-if="poll.started_at">Début : {{ poll.started_at }}</span>
            <span v-if="poll.ends_at">Fin : {{ poll.ends_at }}</span>
          </div>

          <div class="flex flex-wrap gap-2 pt-1">
            <BaseButton variant="blue" @click="showEditForm(poll)" :disabled="!poll.is_draft">
              Modifier
            </BaseButton>
            <BaseButton variant="red" @click="pollToDelete = poll.id" :disabled="loadingId === poll.id">
              {{ loadingId === poll.id ? 'En cours...' : 'Supprimer' }}
            </BaseButton>
            <BaseButton variant="gray" @click="copyLink(poll.secret_token)">
              {{ copied === poll.secret_token ? 'Copié !' : 'Copier le lien' }}
            </BaseButton>
            <a :href="`/vote/${poll.secret_token}`" target="_blank"
              class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-medium text-gray-600 transition-colors hover:bg-gray-100">
              Voir le sondage
            </a>
          </div>
        </div>
      </div>

      <!-- vue pr desktop -->
      <div class="hidden md:block overflow-hidden rounded-xl border border-gray-200 shadow-sm">
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
              <td class="px-4 py-3 font-medium text-gray-800">{{ poll.title || '—' }}</td>
              <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ poll.question }}</td>
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
              <td class="px-4 py-3 text-gray-500 text-xs">{{ poll.started_at ?? '—' }}</td>
              <td class="px-4 py-3 text-gray-500 text-xs">{{ poll.ends_at ?? '—' }}</td>
              <td class="px-4 py-3 flex gap-2 flex-wrap">
                <BaseButton variant="blue" @click="showEditForm(poll)" :disabled="!poll.is_draft">
                  Modifier
                </BaseButton>
                <BaseButton variant="red" @click="pollToDelete = poll.id" :disabled="loadingId === poll.id">
                  {{ loadingId === poll.id ? 'En cours...' : 'Supprimer' }}
                </BaseButton>
                <BaseButton variant="gray" @click="copyLink(poll.secret_token)">
                  {{ copied === poll.secret_token ? 'Copié !' : 'Copier le lien' }}
                </BaseButton>
                <a :href="`/vote/${poll.secret_token}`" target="_blank"
                  class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-medium text-gray-600 transition-colors hover:bg-gray-100">
                  Voir le sondage
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <BaseModal
    v-if="pollToDelete"
    title="Confirmer la suppression"
    message="Cette action est irréversible. Le sondage sera définitivement supprimé."
    confirmLabel="Supprimer"
    confirm-variant="red"
    @confirm="deletePoll"
    @cancel="pollToDelete = null"
  />
</template>