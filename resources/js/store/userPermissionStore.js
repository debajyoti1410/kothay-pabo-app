/**** AUTHENTICATE USER PERMISSIONS HERE ****/
import { defineStore } from "pinia";
import useHttpRequest from "@/composables/useHttpRequest";
import useUserStore from "./useUserStore";

const userPermissionStore = defineStore(
  "permissions",
  () => {
    const userStore = useUserStore();

    const fetchPermissions = async () => {
      const { index: getPermissions } = useHttpRequest();

      const res = await getPermissions("/user/permissions");
      if (res.status === 200) {
        userStore.setPermissions(res.data.permissions);
      }
    };

    return {
      fetchPermissions,
    };
  },
  {
    persist: true,
  }
);

export default userPermissionStore;
