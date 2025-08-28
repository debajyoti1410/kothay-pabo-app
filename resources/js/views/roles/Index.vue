<template>
  <PageTitle title="Roles" cols="3" md="5">
    <VCol cols="9" md="7" class="text-end">
      <!-- CREATE ROLE BUTTON -->
      <VBtn
        @click="openDialog('create')"
        color="primary"
        text="Create Role"
        class="text-capitalize me-2"
        rounded="sm"
        v-if="hasPermission('Role Create')"
      />

      <!-- USER BUTTON -->
      <VBtn
        :to="{ name: 'users' }"
        color="success"
        text="Users"
        class="text-capitalize"
        rounded="sm"
        v-if="hasPermission('User List')"
      />
    </VCol>
  </PageTitle>
  <VRow>
    <VCol cols="12" md="12">
      <TableCard :open-dialog="openDialog" ref="tableCard" />
    </VCol>
  </VRow>

  <!-- SAVE ROLE DIALOG -->
  <SaveDialog
    :isDialogVisible="dialogVisible"
    @update:isDialogVisible="dialogVisible = $event"
    :dialog-title="dialogTitle"
    :load-roles="callLoadRolesMethod"
    :edit-role="editRole"
  />
</template>

<script setup>
import PageTitle from "@/components/PageTitle.vue";
import { hasPermission } from "@/helper/helper";
import SaveDialog from "./components/SaveDialog.vue";
import TableCard from "./components/TableCard.vue";

const tableCard = ref(null);
const callLoadRolesMethod = () => {
  tableCard.value.loadRoles();
};

/** DIALOG */
const dialogVisible = ref(false);
const dialogTitle = ref("Create Role");
const editRole = ref(null);

const openDialog = (type, role = null) => {
  dialogTitle.value = type == "edit" ? "Edit Role" : "Create Role";
  editRole.value = role;
  dialogVisible.value = true;
};
</script>
