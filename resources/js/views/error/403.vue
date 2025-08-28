<template>
  <div class="misc-wrapper">
    <ErrorHeader status-code="403" title="⚠️ Forbidden " description="You don't have permission to access this page." />
    <VBtn :to="{ name: 'dashboard.private' }" color="success" text="Back to dashboard" rounded="sm" />
  </div>
</template>

<script setup>
</script>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
