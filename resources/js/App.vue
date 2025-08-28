<template>
  <VLocaleProvider :rtl="configStore.isAppRTL">
    <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
    <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
      <RouterView />

      <ScrollToTop />
    </VApp>
  </VLocaleProvider>
</template>
<script setup>
import useHttpRequest from "@/composables/useHttpRequest"
import useUserStore from "@/store/useUserStore"
import ScrollToTop from '@core/components/ScrollToTop.vue'
import initCore from '@core/initCore'
import {
  initConfigStore,
  useConfigStore,
} from '@core/stores/config'
import { hexToRgb } from '@core/utils/colorConverter'
import { useTheme } from 'vuetify'

const { global } = useTheme()

// ℹ️ Sync current theme with initial loader theme
initCore()
initConfigStore()

const configStore = useConfigStore()
let lastActivityTime = Date.now();
const { get } = useHttpRequest();
const userStore = useUserStore();

const fetchPermissions = async () => {
  const { get } = useHttpRequest();
  const res = await get("/user/permissions");
  if (res.status === 200) {
    userStore.setPermissions(res.data.permissions);
  }

  return true;
}

watch(() => userStore.authUser, (user) => {
  if (user) {
    fetchPermissions();
  }
}, { immediate: true });
</script>
