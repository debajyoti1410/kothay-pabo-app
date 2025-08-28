<template>
  <div
    class="auth-wrapper d-flex align-center justify-center pa-4 background-img"
    :style="{
      backgroundImage:
        'linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5) ),url(' +
        loginImg +
        ')',
    }"
  >
    <div class="my-sm-16">
      <!-- 👉 Auth Card -->
      <VCard
        class="auth-card"
        max-width="460"
        :class="$vuetify.display.smAndUp ? 'pa-6' : 'pa-0'"
      >
        <VCardItem class="justify-center">
          <VCardTitle>
            <RouterLink to="/">
              <div class="app-logo">
                <VImg :src="logo" width="50" alt="Brand Logo" />
              </div>
            </RouterLink>
          </VCardTitle>
        </VCardItem>

        <VCardText class="login-title">
          <!-- <h4 class="text-h4 mb-1">
            Welcome to <span class="brand-title">{{ themeConfig.app.title }}</span>
          </h4> -->
          <p class="mb-0">
            Please sign-in to your account to access the admin panel
          </p>
        </VCardText>

        <VCardText>
          <VForm @submit.prevent="loginAction">
            <VRow>
              <!-- EMAIL -->
              <VCol cols="12">
                <AppTextField
                  v-model="email.value.value"
                  autofocus
                  label="Email"
                  required="true"
                  type="email"
                  placeholder="Enter your email"
                  :error-messages="email.errorMessage.value"
                />
              </VCol>

              <!-- PASSWORD -->
              <VCol cols="12">
                <AppTextField
                  v-model="password.value.value"
                  label="Password"
                  required="true"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  :error-messages="password.errorMessage.value"
                />

                <div
                  class="d-flex align-center flex-wrap justify-space-between my-6"
                >
                  <VCheckbox
                    label="Remember me"
                    v-model="remember_me.value.value"
                  />
                  <!-- <router-link class="text-primary" :to="{ name: 'forgotPassword' }">
                    Forgot Password?
                  </router-link> -->
                </div>

                <!-- SUBMIT BUTTON -->
                <VBtn
                  block
                  type="submit"
                  :loading="loading"
                  :disabled="loading"
                >
                  Login
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </div>
  </div>

  <!-- SNACKBAR -->
  <SnackBar :snackbar="snackDetail" />
</template>

<script setup>
import SnackBar from "@/components/SnackBar.vue";
import useHttpRequest from "@/composables/useHttpRequest";
import useAuth from "@/store/useAuth";
import useUserStore from "@/store/useUserStore";
// import loginImg from "@images/login.webp";
import loginImg from "@images/login-1.jpg";
import logo from "@images/logo.png";
import { useField, useForm } from "vee-validate";
import { useRouter } from "vue-router";
import { VForm } from "vuetify/components/VForm";
import * as Yup from "yup";

const isPasswordVisible = ref(false);

const router = useRouter();
const userStore = useUserStore();
const auth = useAuth();
const snackDetail = ref({
  show: false,
  color: "success",
  message: null,
});

const { post, loading } = useHttpRequest();

/******* VALIDATION *******/
const validationSchema = Yup.object().shape({
  email: Yup.string()
    .required("This field is required")
    .matches(
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
      "email must be a valid email"
    )
    .max(100),
  password: Yup.string().required("This field is required").min(8).max(115),
});

const { handleSubmit, setErrors } = useForm({
  validationSchema,
});

const email = useField("email");
const password = useField("password");
const remember_me = useField("remember_me");

// email.value.value = "superadmin@gmail.com";
// password.value.value = "superadmin2001";

/******* LOGIN ACTION *******/
const loginAction = handleSubmit(async (values) => {
  const { status, message, errors, data } = await post("/login", values);

  if (status == 422) {
    setErrors(errors);
  }

  if (status == 401) {
    snackDetail.value.show = true;
    snackDetail.value.color = "error";
    snackDetail.value.message = message;
  }

  if (status == 500) {
    snackDetail.value.show = true;
    snackDetail.value.color = "error";
    snackDetail.value.message =
      "Internal Server Error. Please try again later.";
  }

  if (status == 200) {
    snackDetail.value.show = true;
    snackDetail.value.color = "success";
    snackDetail.value.message = message;

    let userData = {
      id: data.user.id,
      name: data.user.name,
      email: data.user.email,
      phone: data.user.phone,
      address: data.user.address,
      role: data.user.role,
    };

    userStore.setUser(userData);
    auth.setToken(data.user.access_token);
    router.push({ name: "dashboard" });
  }
});
</script>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";

.background-img {
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
</style>
