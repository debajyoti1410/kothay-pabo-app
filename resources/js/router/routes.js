/** ALL ROUTES ARE DEFINE HERE */
export default [
  { path: "/", redirect: "/login" },
  {
    path: "/",
    component: () => import("../layouts/default.vue"),
    children: [
      /** DASHBOARD ADVANCE */
      {
        path: "dashboard",
        name: "dashboard",
        component: () => import("../views/dashboard/Index.vue"),
        meta: {
          auth: true,
          permissions: "",
        },
      },
      /** USERS */
      {
        path: "users",
        name: "users",
        component: () => import("../views/users/Index.vue"),
        meta: {
          auth: true,
          permissions: "User List",
        },
      },
      /** ROLES */
      {
        path: "roles",
        name: "roles",
        component: () => import("../views/roles/Index.vue"),
        meta: {
          auth: true,
          permissions: "Role List",
        },
      },
    ],
  },
  /** LOGIN & FALLBACK */
  {
    path: "/",
    component: () => import("../layouts/blank.vue"),
    children: [
      {
        path: "login",
        name: "login",
        component: () => import("../views/auth/login.vue"),
        meta: {
          auth: false,
        },
      },
      {
        path: "forbidden",
        name: "forbidden",
        component: () => import("../views/error/403.vue"),
        meta: {
          forbidden: true,
        },
      },
      {
        path: "/:pathMatch(.*)*",
        component: () => import("../views/error/404.vue"),
        meta: {
          fallback: true,
        },
      },
    ],
  },
];
