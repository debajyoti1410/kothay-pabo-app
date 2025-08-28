<template>
  <div class="misc-wrapper">
    <ErrorHeader status-code="404" title="⚠️ Page Not Found"
      description="We couldn't find the page you are looking for." />
    <VBtn :to="{ name: 'dashboard.private' }" color="success" text="Back to dashboard" rounded="sm" />
  </div>
</template>

<script setup>
</script>

<style lang="scss">
@use "@core-scss/template/pages/misc.scss";
</style>
