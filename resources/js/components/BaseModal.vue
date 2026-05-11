<script setup>
defineProps({
  title: { type: String, required: true },
  message: { type: String, required: true },
  confirmLabel: { type: String, default: 'Confirmer' },
  cancelLabel: { type: String, default: 'Annuler' },
  confirmClass: { type: String, default: 'rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700' },
});

defineEmits(['confirm', 'cancel']);
</script>

<template>
  <div class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-sm w-full mx-4">
      <h2 class="text-base font-semibold text-gray-900 mb-2">{{ title }}</h2>
      <p class="text-sm text-gray-500 mb-6">{{ message }}</p>
      <div class="flex gap-3 justify-end">
        <button @click="$emit('cancel')"
          class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">
          {{ cancelLabel }}
        </button>
        <button @click="$emit('confirm')" :class="confirmClass">
          {{ confirmLabel }}
        </button>
      </div>
    </div>
  </div>
</template>