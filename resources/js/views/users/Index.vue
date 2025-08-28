<template>
  <PageTitle title="Users" cols="3" md="5">
    <VCol cols="9" md="7" class="text-end">
      <!-- CREATE USER BUTTON -->
      <VBtn
        @click="openDialog('create')"
        color="primary"
        text="Create User"
        class="text-capitalize me-2"
        rounded="sm"
        v-if="hasPermission('User Create')"
      />

      <!-- ROLE BUTTON -->
      <VBtn
        :to="{ name: 'roles' }"
        color="success"
        text="Roles"
        class="text-capitalize"
        rounded="sm"
        v-if="hasPermission('Role List')"
      />
    </VCol>
  </PageTitle>
  <VRow>
    <VCol cols="12" md="12">
      <TableCard :open-dialog="openDialog" ref="tableCard" />
    </VCol>
  </VRow>

  <!-- SAVE USER DIALOG -->
  <SaveDialog
    :isDialogVisible="dialogVisible"
    @update:isDialogVisible="dialogVisible = $event"
    :dialog-title="dialogTitle"
    :load-users="callLoadUsersMethod"
    :edit-user="editUser"
  />
</template>

<script setup>
import PageTitle from "@/components/PageTitle.vue";
import { hasPermission } from "@/helper/helper";
import SaveDialog from "./components/SaveDialog.vue";
import TableCard from "./components/TableCard.vue";


/** DIALOG */
const dialogVisible = ref(false);
const dialogTitle = ref("Create User");
const editUser = ref(null);

const openDialog = (type, user = null) => {
  dialogTitle.value = type == "edit" ? "Edit User" : "Create User";
  editUser.value = user;
  dialogVisible.value = true;
};

const tableCard = ref(null);
const callLoadUsersMethod = () => {
  tableCard.value.loadUsers();
};
</script>
