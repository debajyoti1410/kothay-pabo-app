<template>
  <VSnackbar v-model="snackbar.show" :color="snackbar.color" location="top right" 
    transition="scroll-y-reverse-transition">
    {{ snackbar.message }}

    <template #actions>
      <VBtn color="#ffff" @click="snackbar.show = false" prepend-icon="tabler-x" />
    </template>
  </VSnackbar>
</template>

<script setup>

/** PROPS */
const props = defineProps({
  snackbar: Object,
});
</script>
