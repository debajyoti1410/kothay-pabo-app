export default [
  {
    title: "Dashboard",
    to: { name: "dashboard" },
    icon: { icon: "tabler-home" },
    permissions: [],
  },
  // {
  //   title: "Setup",
  //   icon: { icon: "tabler-folder-cog" },
  //   permissions: ["Lead Status List"],
  //   children: [
  //     {
  //       title: "Lead Status",
  //       to: { name: "lead.status" },
  //       permissions: ["Lead Status List"],
  //     },
  //     {
  //       title: "Teams",
  //       to: { name: "teams" },
  //       permissions: ["Teams List"],
  //     },
  //   ],
  // },
  {
    title: "Users & Permissions",
    to: { name: "users" },
    icon: { icon: "tabler-user-circle" },
    permissions: ["User List"],
  },
];
