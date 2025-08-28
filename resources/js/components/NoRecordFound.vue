<template>
  <!-- NO RECORD FOUND -->
  <div class="h-60">
    <div class="d-flex w-100 h-100 flex-column justify-center align-center">
      <VIcon icon="tabler-file-alert" size="64" />
      <h5 class="text-h5 fs-14 fw-600">No Record Found</h5>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from "vue";
// Define props
const props = defineProps({
  colspan: String,
});
</script>

<style lang="scss" scoped></style>
