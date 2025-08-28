<template>
  <div class="search-field">
    <!-- SEARCH FIELD -->
    <AppTextField v-model="searchQuery" placeholder="Search here" />
  </div>
  <VRow class="mt-2" v-if="loader">
    <VCol cols="12" lg="3" md="4" sm="6" v-for="i in 16">
      <VCard class="main-card users-card">
        <VCardItem>
          <div class="card-contents">
            <v-skeleton-loader type="paragraph"></v-skeleton-loader>
          </div>
        </VCardItem>
      </VCard>
    </VCol>
  </VRow>

  <VRow class="mt-2" v-if="!loader && users.length > 0">
    <VCol
      cols="12"
      lg="3"
      md="4"
      sm="6"
      class=""
      v-for="(user,index) in users"
      :key="user.id"
    >
      <VCard class="main-card users-card">
        <VCardItem class="res-align-start cursor-pointer">
          <div class="card-contents" :ref="(el) => (cardContentRefs[index] = el)">
            <div class="main-contents" :ref="(el) => (mainContentRefs[index] = el)">
              <div class="d-flex justify-space-between">
                <h5 class="fs-14 res-fs-12 fw-600">
                  {{ user.name }}
                  <span class="ms-1">
                    <VChip
                      color="success"
                      size="small"
                      :label="false"
                      class="res-fs-11 fs-12"
                      v-for="userRole in user.roles"
                      :key="userRole.id"
                    >
                      {{ userRole.name }}
                    </VChip>
                  </span>
                </h5>
                <div class="btns" v-if="hasPermission('User Edit')">
                  <!-- EDIT -->
                  <VTooltip location="bottom">
                    <template #activator="{ props }">
                      <VIcon
                        v-bind="props"
                        icon="tabler-edit"
                        size="18"
                        color="info"
                        @click="openDialog('edit', user)"
                      />
                    </template>
                    <span>Edit</span>
                  </VTooltip>
                </div>
              </div>
              <p class="mb-0 fs-12 fs-xs-11">
                <a :href="`tel:+91${user.phone}`">{{ user.phone }}</a>
              </p>
              <p class="mb-0 fs-12 fs-xs-11">
                <a :href="`mailto:${user.email}`">{{ user.email }}</a>
              </p>
              <p class="mb-0 fs-12 fs-xs-11">
                {{ user.address }}
              </p>
            </div>
          </div>
          <div class="more-btn pt-1">
            <div
              class="fs-12 fs-xs-11 fs-sm-11 text-primary fw-600 cursor-pointer show-more-btn"
              @click="showFullText"
              v-if="showMoreBtnVisible(index)"
            >
              Show more
            </div>
          </div>
        </VCardItem>
      </VCard>
    </VCol>
  </VRow>

  <!-- NO RECORD FOUND CART -->
  <VRow class="mt-2" v-if="!loader && users.length == 0">
    <VCol cols="12">
      <VCard>
        <VCardText>
          <NoRecordFound />
        </VCardText>
      </VCard>
    </VCol>
  </VRow>

  <VRow class="mt-2" v-if="users.length > 0 && pagination.total != 1">
    <VCol cols="12" md="12" lg="12">
      <div class="float-end">
        <VPagination
          v-model="pagination.current"
          :length="pagination.total"
          active-color="primary"
          @update:modelValue="onPageChange"
          :total-visible="$vuetify.display.mdAndUp ? 5 : 3"
        />
      </div>
    </VCol>
  </VRow>
</template>
<script setup>
import NoRecordFound from "@/components/NoRecordFound.vue";
import useHttpRequest from "@/composables/useHttpRequest";
import { useShowMore } from "@/composables/useShowMore";
import { hasPermission, showFullText } from "@/helper/helper";
import debounce from "lodash.debounce";

// Define props
const props = defineProps({
  openDialog: Function,
});

/** MOUNTED */
onMounted(() => {
  loadUsers();
});

/** PAGINATION */
const pagination = ref({
  current: 1,
  total: 0,
});

const onPageChange = () => {
  loadUsers();
};

/** USERS DATA */
const users = ref({ data: [] });
const { get, loader } = useHttpRequest();
const {
  mainContentRefs,
  cardContentRefs,
  showMoreBtnVisible,
  recalculateOnNextTick,
} = useShowMore();

const loadUsers = async () => {
  let params = {
    page: pagination.value.current,
    paginate: true
  }
  const { status, message, data } = await get(
    `/users`,
    params
  );

  if (status == 200) {
    users.value = data.data;
    pagination.value.current = data.current_page;
    pagination.value.total = data.last_page;
    await recalculateOnNextTick();
  }
};

/** SEARCH */
const searchQuery = ref(null);

watch(
  searchQuery,
  debounce(() => {
    search();
  }, 300)
);

const search = async () => {
  const params = ref({
    search: searchQuery.value,
  });

  const result = await get("/users/search", params.value, false);

  if (result.status == 200) {
    users.value = result.data.users.data;
    pagination.value.current = result.data.users.current_page;
    pagination.value.total = result.data.users.last_page;
    await recalculateOnNextTick();
  }
};

defineExpose({
  loadUsers,
});
</script>
