<template>
  <VDialog
    :model-value="isDialogVisible"
    @update:model-value="closeDialog"
    max-width="600"
  >
    <!-- DIALOG CLOSE BUTTON -->
    <DialogCloseBtn @click="closeDialog" />

    <!-- DIALOG CONTENT -->
    <VCard :title="dialogTitle">
      <VForm @submit.prevent="submitForm">
        <VCardText class="pt-0">
          <VRow>
            <!-- ID -->
            <VCol cols="12" sm="6" md="6" class="d-none">
              <AppTextField
                label="id"
                required="true"
                placeholder="Enter id"
                v-model="id.value.value"
                :error-messages="id.errorMessage.value"
              />
            </VCol>

            <!-- NAME -->
            <VCol cols="12" sm="6" md="6">
              <AppTextField
                label="Name"
                required="true"
                placeholder="Enter name"
                v-model="name.value.value"
                :error-messages="name.errorMessage.value"
              />
            </VCol>
            <!-- PHONE -->
            <VCol cols="12" sm="6" md="6">
              <AppTextField
                label="Phone"
                required="true"
                placeholder="Enter no"
                v-model="phone.value.value"
                :error-messages="phone.errorMessage.value"
              />
            </VCol>
            <!-- EMAIL -->
            <VCol cols="12" md="6">
              <AppTextField
                label="Email"
                required="true"
                placeholder="Enter email"
                v-model="email.value.value"
                :error-messages="email.errorMessage.value"
              />
            </VCol>
            <!-- ROLE -->
            <VCol cols="12" md="6">
              <AppSelect
                label="Role"
                required="true"
                placeholder="Select role"
                :items="roles"
                item-title="name"
                item-value="id"
                v-model="role.value.value"
                :error-messages="role.errorMessage.value"
              />
            </VCol>
            <!-- PASSWORD -->
            <VCol cols="12" md="6" v-if="dialogTitle === `Create User`">
              <AppTextField
                :type="passwordType ? 'text' : 'password'"
                :append-inner-icon="
                  passwordType ? 'tabler-eye-off' : 'tabler-eye'
                "
                @click:append-inner="passwordType = !passwordType"
                label="Password"
                required="true"
                placeholder="············"
                v-model="password.value.value"
                :error-messages="confirm_password.errorMessage.value"
              />
            </VCol>
            <!-- CONFIRM PASSWORD -->
            <VCol cols="12" md="6" v-if="dialogTitle === `Create User`">
              <AppTextField
                label="Confirm Password"
                required="true"
                placeholder="············"
                :type="CPasswordType ? 'text' : 'password'"
                :append-inner-icon="
                  CPasswordType ? 'tabler-eye-off' : 'tabler-eye'
                "
                @click:append-inner="CPasswordType = !CPasswordType"
                v-model="confirm_password.value.value"
                :error-messages="confirm_password.errorMessage.value"
              />
            </VCol>
            <!-- ADDRESS -->
            <VCol cols="12" md="12">
              <AppTextarea
                label="Address"
                rows="2"
                placeholder="Write here"
                v-model="address.value.value"
                :error-messages="address.errorMessage.value"
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

// Define props
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  dialogTitle: String,
  loadUsers: Function,
  editUser: Object,
});

/** MOUNTED */
onMounted(() => {
  loadRoles();
});

const passwordType = ref(false);
const CPasswordType = ref(false);

const snackDetail = ref({
  show: false,
  color: "success",
  message: null,
});

const { get, loader, post, loading } = useHttpRequest();

/** GET ROLES */
const roles = ref([]);
const loadRoles = async () => {
  const res = await get("/roles");
  roles.value = res.data.data;
};

/******* VALIDATION *******/
const createValidationSchema = Yup.object().shape({
  name: Yup.string()
    .required("This field is required")
    .matches(/^[a-zA-Z ]+$/, "This field only contain letters and spaces.")
    .min(3, "Please enter a name with at least 3 characters.")
    .max(200),
  phone: Yup.string()
    .required("This field is required")
    .matches(/^\d+$/, "Only numbers are allowed")
    .min(10, "Please enter a valid 10-digit number.")
    .max(10, "Please enter a valid 10-digit number."),
  email: Yup.string()
    .required("This field is required")
    .matches(
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
      "email must be a valid email"
    )
    .max(200),
  role: Yup.string().required("This field is required"),
  password: Yup.string().required("This field is required").min(8).max(10),
  confirm_password: Yup.string()
    .required("This field is required")
    .min(8)
    .oneOf([Yup.ref("password"), null], "Passwords must match"),
});

const editValidationSchema = Yup.object().shape({
  name: Yup.string().required("This field is required"),
  email: Yup.string().email().required("This field is required"),
  role: Yup.string().required("This field is required"),
});

const validationSchema = computed(() => {
  return props.dialogTitle === "Edit User"
    ? editValidationSchema
    : createValidationSchema;
});

const { handleSubmit, setErrors, resetForm } = useForm({
  validationSchema,
});

const id = useField("id");
const name = useField("name");
const phone = useField("phone");
const role = useField("role");
const email = useField("email");
const password = useField("password");
const confirm_password = useField("confirm_password");
const address = useField("address");

const setFields = (val) => {
  id.value.value = val.id;
  name.value.value = val.name;
  phone.value.value = val.phone;
  role.value.value = val.roles[0]?.id;
  email.value.value = val.email;
  address.value.value = val.address;
};

/** EDIT USER VALUE */
watch(
  () => props.isDialogVisible,
  (val) => {
    if (val && props.editUser != null) {
      setFields(props.editUser);
    }
  },
  { immediate: true }
);

const submitForm = handleSubmit(async (values) => {
  const { status, data, errors, message } = await post("/user/store", values);
  
  if (status == 422) {
    setErrors(errors);
  }

  if (status == 200) {
    snackDetail.value.show = true;
    snackDetail.value.color = "success";
    snackDetail.value.message = message;
    closeDialog();
    props.loadUsers();
  }
});

// Close the dialog
const emit = defineEmits(["update:isDialogVisible"]);
const closeDialog = () => {
  resetForm({
    values: {
      id: "",
      name: "",
      phone: "",
      role: [],
      email: "",
      password: "",
      confirm_password: "",
      address: "",
    },
  });
  emit("update:isDialogVisible", false);
};
</script>
