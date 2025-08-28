<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    bordered
    color="success"
    class="ms-3"
  >
    <VAvatar class="cursor-pointer" color="primary" variant="tonal">
      <div class="img-title">{{ imgTitle }}</div>

      <!-- SECTION Menu -->
      <VMenu activator="parent" width="230" location="bottom end" offset="14px">
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar color="primary" variant="tonal">
                    <div class="img-title">{{ imgTitle }}</div>
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold text-capitalize">
              {{ authUser?.name }}
            </VListItemTitle>
            <VListItemSubtitle>{{ authUser?.role[0] }}</VListItemSubtitle>
          </VListItem>

          <VDivider class="my-2" />

          <!-- Profile -->
          <!-- <VListItem :to="{ name: 'edit_profile' }">
            <template #prepend>
              <VIcon class="me-2" icon="tabler-user" size="22" />
            </template>

            <VListItemTitle>Edit Profile</VListItemTitle>
          </VListItem> -->

          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <div class="px-4 py-2">
            <VBtn
              block
              size="small"
              color="error"
              append-icon="tabler-logout"
              @click="loggedOut"
            >
              Logout
            </VBtn>
          </div>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>

  <SnackBar :snackbar="snackDetail" />
</template>

<script setup>
import SnackBar from "@/components/SnackBar.vue";
import useHttpRequest from "@/composables/useHttpRequest.js";
import useAuth from "@/store/useAuth.js";
import useUserStore from "@/store/useUserStore";
import { useRouter } from "vue-router";

const userStore = useUserStore();
const auth = useAuth();
const authUser = userStore.authUser;
const imgTitle = authUser.name.charAt(0).toUpperCase();

const router = useRouter();
const { get } = useHttpRequest();

const snackDetail = ref({
  show: false,
  color: "success",
  message: null,
});

const loggedOut = async () => {
  const isLoggedOut = await get("/logout");
  if (isLoggedOut.status == 200) {
    snackDetail.value.show = true;
    snackDetail.value.color = "success";
    snackDetail.value.message = isLoggedOut.data.message;

    userStore.setUser(null);
    userStore.removePermissions();
    auth.removeToken();

    window.location.reload();
  }
};
</script>
