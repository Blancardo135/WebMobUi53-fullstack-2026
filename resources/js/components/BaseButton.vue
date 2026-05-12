<script setup>
defineProps({
  variant: { type: String, default: 'gray' },
  disabled: { type: Boolean, default: false },
});

defineEmits(['click']);
</script>

<template>
  <button
    @click="$emit('click')"
    :disabled="disabled"
    :class="[
      'rounded-lg border px-3 py-1.5 text-xs font-medium transition-colors disabled:cursor-not-allowed disabled:opacity-40',
      variant === 'blue' && 'border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100',
      variant === 'red'  && 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100',
      variant === 'gray' && 'border-gray-200 bg-gray-50 text-gray-600 hover:bg-gray-100',
    ]"
  >
    <slot></slot>
  </button>
</template>