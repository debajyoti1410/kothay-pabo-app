<template>
  <VDialog
    :model-value="isDialogVisible"
    @update:model-value="closeDialog"
    max-width="450"
  >
    <!-- DIALOG CLOSE BUTTON -->
    <DialogCloseBtn @click="closeDialog" />

    <!-- DIALOG CONTENT -->
    <VCard :title="dialogTitle">
      <VForm @submit.prevent="submitForm">
        <VCardText class="pt-0">
          <VRow>
            <!-- ID -->
            <VCol cols="12" class="d-none">
              <AppTextField
                label="Id"
                required="true"
                placeholder="Enter id"
                v-model="id.value.value"
                :error-messages="id.errorMessage.value"
              />
            </VCol>

            <!-- NAME -->
            <VCol cols="12">
              <AppTextField
                label="Name"
                required="true"
                placeholder="Enter role name"
                v-model="name.value.value"
                :error-messages="name.errorMessage.value"
              />
            </VCol>
            <!-- PERMISSIONS -->
            <VCol cols="12">
              <AppSelect
                label="Permissions"
                required="true"
                placeholder="Select permissions"
                :items="permissions"
                item-title="name"
                item-value="id"
                chips
                multiple
                closable-chips
                v-model="permission.value.value"
                :error-messages="permission.errorMessage.value"
              />
            </VCol>
          </VRow>
        </VCardText>

        <!-- DIALOG ACTION BUTTON -->
        <VCardText class="d-flex justify-end flex-wrap gap-3">
          <!-- DIALOG CLOSE BUTTON -->
          <VBtn color="secondary" variant="tonal" @click="closeDialog">
            Cancel
          </VBtn>
          <!-- SAVE BUTTON -->
          <VBtn
            color="success"
            type="submit"
            :loading="loading"
            :disabled="loading"
          >
            Save
          </VBtn>
        </VCardText>
      </VForm>
    </VCard>
  </VDialog>

  <!-- SNACKBAR -->
  <SnackBar :snackbar="snackDetail" />
</template>
<script setup>
import SnackBar from "@/components/SnackBar.vue";
import useHttpRequest from "@/composables/useHttpRequest";
import { useField, useForm } from "vee-validate";
import { VForm } from "vuetify/components/VForm";
import * as Yup from "yup";

/** DEFINE PROPS */
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  dialogTitle: String,
  loadRoles: Function,
  editRole: Object,
});

/** MOUNTED */
onMounted(() => {
  loadPermission();
});

const { get, post, loading } = useHttpRequest();

const snackDetail = ref({
  show: false,
  color: "success",
  message: null,
});

/******* VALIDATION *******/
const validationSchema = Yup.object().shape({
  name: Yup.string().required("This field is required"),
  permission: Yup.array().required("This field is required"),
});

const { handleSubmit, setErrors, resetForm } = useForm({
  validationSchema,
});

const id = useField("id");
const name = useField("name");
const permission = useField("permission");

/** GET PERMISSIONS */
const permissions = ref([]);
const loadPermission = async () => {
  const res = await get("/permissions");
  permissions.value = res.data.permissions;
};

const setFields = (val) => {
  id.value.value = val.id;
  name.value.value = val.name;
  permission.value.value = val.permissions;
};

/** WATCH */
watch(
  () => props.isDialogVisible,
  (val) => {
    if (val && props.editRole != null) {
      setFields(props.editRole);
    }
  },
  { immediate: true }
);

const submitForm = handleSubmit(async (values) => {
  const res = await post("/role/store", values);

  if (res.status == 422) {
    setErrors(res.data.errors);
  }

  if (res.status == 200) {
    snackDetail.value.show = true;
    snackDetail.value.color = "success";
    snackDetail.value.message = res.message;
    closeDialog();
    props.loadRoles();
  }
});

/** CLOSE DIALOGS */
const emit = defineEmits(["update:isDialogVisible"]);
const closeDialog = () => {
  resetForm({
    values: { id: "", name: "", permission: [] },
  });
  emit("update:isDialogVisible", false);
};
</script>
