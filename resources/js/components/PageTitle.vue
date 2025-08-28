<template>
  <!-- PAGE TILE -->
  <VRow :class="rowClass">
    <VCol :cols="cols" :md="md">
      <h2 class="text-h4 page-title">{{ title }}</h2>
    </VCol>
    <slot />
  </VRow>
</template>
<script setup>

/** PROPS */
const props = defineProps({
  title: String,
  cols: String,
  md: String,
  rowClass: String
});
</script>
