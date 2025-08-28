/**** AUTHENTICATE USER INFO HERE ****/
import { defineStore } from "pinia";

const useUserStore = defineStore(
  "users",
  () => {
    const authUser = ref(null);
    const permissions = ref([]);

    const setUser = (user) => {
      authUser.value = user;
    };

    const setPermissions = (p) => {
      permissions.value = p;
    }
    
    const removePermissions = () => {
      permissions.value = [];
    }

    return {
      authUser,
      setUser,
      setPermissions,
      permissions,
      removePermissions
    };
  },
  {
    persist: true,
  }
);

export default useUserStore;
