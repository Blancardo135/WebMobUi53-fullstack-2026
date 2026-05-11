<template>
  <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-sm w-full mx-4">
      <h2 class="text-base font-semibold text-gray-900 mb-2">Confirmer la suppression</h2>
      <p class="text-sm text-gray-500 mb-6">Cette action est irréversible. Le sondage sera définitivement supprimé.</p>
      <div class="flex gap-3 justify-end">
        <button
          @click="$emit('cancel')"
          class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors"
        >
          Annuler
        </button>
        <button
          @click="$emit('confirm')"
          class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600 transition-colors"
        >
          Supprimer
        </button>
      </div>
    </div>
  </div>
</template>