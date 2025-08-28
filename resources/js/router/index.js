// import { userPermissions } from "@/helper/helper";
import useAuth from "@/store/useAuth";
import useUserStore from "@/store/useUserStore";
import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";

const router = createRouter({
  history: createWebHistory("/"),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const auth = useAuth();
  const permissions = useUserStore().permissions;
  // const userPermissions = userPermissionStore();

  if (to.meta.fallback) {
    return next();
  }

  if (to.meta.forbidden) {
    return next();
  }

  if (to.meta.auth) {
    if (auth.getToken != 0) {
      // Check user permissions
      // await userPermissions();
      if (
        !permissions.includes(to.meta.permissions) &&
        to.name != "dashboard"
      ) {
        return next({ name: "forbidden" });
      }
      return next();
    } else {
      return next({ name: "login" });
    }
  } else {
    if (auth.getToken == 0) {
      return next();
    } else {
      return next({ name: "dashboard" });
    }
  }
});

export default router;
