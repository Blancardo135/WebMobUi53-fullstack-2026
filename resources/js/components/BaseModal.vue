<script setup>
import BaseButton from './BaseButton.vue';
defineProps({
  title: { type: String, required: true },
  message: { type: String, required: true },
  confirmLabel: { type: String, default: 'Confirmer' },
  cancelLabel: { type: String, default: 'Annuler' },
  confirmVariant: { type: String, default: 'red' },
});

defineEmits(['confirm', 'cancel']);
</script>

<template>
  <div class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-lg p-6 max-w-sm w-full mx-4">
      <h2 class="text-base font-semibold text-gray-900 mb-2">{{ title }}</h2>
      <p class="text-sm text-gray-500 mb-6">{{ message }}</p>
      <div class="flex gap-3 justify-end">
        <BaseButton variant="gray" @click="$emit('cancel')">
          {{ cancelLabel }}
        </BaseButton>
        <BaseButton :variant="confirmVariant" @click="$emit('confirm')">
          {{ confirmLabel }}
        </BaseButton>
      </div>
    </div>
  </div>
</template>