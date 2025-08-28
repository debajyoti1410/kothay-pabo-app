<template>
  <VCard>
    <VCardText
      class="d-flex justify-space-between align-center flex-wrap gap-4"
    >
      <div style="inline-size: 272px">
        <!-- SEARCH FIELD -->
        <AppTextField v-model="searchQuery" placeholder="Search here" />
      </div>
    </VCardText>

    <v-skeleton-loader type="article" v-if="loader"></v-skeleton-loader>

    <!-- TABLE DATA -->
    <VTable density="compact" class="mb-4" v-else>
      <thead v-if="roles.length > 0">
        <tr>
          <th style="width: 10%">Role Name</th>
          <th style="width: 50%">Permissions</th>
          <th style="width: 8%" v-if="hasPermission('Role Edit')">
            Action
          </th>
        </tr>
      </thead>

      <tbody v-if="roles.length > 0">
        <tr v-for="role in roles" :key="role.id">
          <td>{{ role.name }}</td>
          <td>
            <VChip
              v-for="permission in role.permissions"
              :key="permission.id"
              color="success"
              size="small"
              class="me-2 my-2"
              :label="false"
            >
              {{ permission.name }}
            </VChip>
          </td>
          <td v-if="hasPermission('Role Edit')">
            <div class="d-flex">
              <!-- EDIT BUTTON -->
              <VBtn
                color="info"
                variant="text"
                @click="openDialog('edit', role)"
                class="custom-btn-1"
              >
                <VIcon start icon="tabler-edit" size="20" />Edit
              </VBtn>
            </div>
          </td>
        </tr>
      </tbody>

      <!-- NO RECORD FOUND -->
      <tbody v-else>
        <NoRecordFound colspan="5" />
      </tbody>
    </VTable>

    <!-- PAGINATION -->
    <VCardText
      class="d-flex justify-end flex-wrap gap-4"
      v-if="roles.length > 0 && pagination.total != 1"
    >
      <VPagination
        v-model="pagination.current"
        :length="pagination.total"
        active-color="primary"
        @update:modelValue="onPageChange"
        :total-visible="$vuetify.display.mdAndUp ? 5 : 3"
      />
    </VCardText>
  </VCard>
</template>
<script setup>
import NoRecordFound from "@/components/NoRecordFound.vue";
import useHttpRequest from "@/composables/useHttpRequest";
import { hasPermission } from "@/helper/helper";
import debounce from "lodash.debounce";

// Define props
const props = defineProps({
  openDialog: Function,
});

/** MOUNTED */
onMounted(() => {
  loadRoles();
});

/** PAGINATION */
const pagination = ref({
  current: 1,
  total: 0,
});

const onPageChange = () => {
  loadRoles();
};

/** ROLES DATA */
const roles = ref({ data: [] });
const { get, loader } = useHttpRequest();

const loadRoles = async () => {
  const res = await get(`/roles?page=${pagination.value.current}`);
  roles.value = res.data.data;
  pagination.value.current = res.data.current_page;
  pagination.value.total = res.data.last_page;
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

  const result = await get("/role/search", params.value, false);

  if (result.status == 200) {
    roles.value = result.data.roles.data;
    pagination.value.current = result.data.roles.current_page;
    pagination.value.total = result.data.roles.last_page;
  }
};

defineExpose({
  loadRoles,
});
</script>
