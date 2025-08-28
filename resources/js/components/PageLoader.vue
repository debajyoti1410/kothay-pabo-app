<template>
  <!-- PAGE LOADER -->
  <VRow :class="className">
    <VCol cols="12" md="12" class="text-center">
      <VProgressCircular :size="50" width="3" color="primary" indeterminate />
    </VCol>
  </VRow>
</template>
<script setup>

const props = defineProps({
  className: {
    type: String,
    default: 'h-70 align-center'
  },
});
</script>
