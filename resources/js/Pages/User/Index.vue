<template>
  <AppLayout title="Manajemen User">
    <DataTable :items="users" :col-count="4">
      <template #actions>
        <Link :href="route('users.create')" class="btn btn-primary">
          <i class="fas fa-plus" style="font-size:11px"></i> Tambah User
        </Link>
      </template>
      <template #headers>
        <th class="table-th">Username</th>
        <th class="table-th">Role</th>
        <th class="table-th">Dibuat</th>
      </template>
      <template #row="{ row }">
        <td class="table-td font-medium">{{ row.username }}</td>
        <td class="table-td">
          <span :class="['badge', roleBadge(row.role)]">{{ row.role }}</span>
        </td>
        <td class="table-td-muted text-xs">{{ formatDate(row.created_at) }}</td>
      </template>
      <template #rowActions="{ row }">
        <Link :href="route('users.edit', row.id)" class="btn-icon btn-icon-edit" title="Edit">
          <i class="fas fa-pencil-alt text-xs"></i>
        </Link>
        <button @click="confirmDelete(row)" class="btn-icon btn-icon-danger" title="Hapus">
          <i class="fas fa-trash text-xs"></i>
        </button>
      </template>
    </DataTable>

    <DeleteModal v-if="deleteTarget" :name="deleteTarget.username"
      @confirm="doDelete" @cancel="deleteTarget = null" />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import DeleteModal from '@/Components/DeleteModal.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({ users: Array })
const deleteTarget = ref(null)

function confirmDelete(row) { deleteTarget.value = row }
function doDelete() {
  router.delete(route('users.destroy', deleteTarget.value.id), {
    onFinish: () => { deleteTarget.value = null },
  })
}

const ROLE_BADGE = {
  ADMIN: 'badge-red',
  IPDS: 'badge-indigo',
  SUBJECT_MATTER: 'badge-amber',
  GUEST: 'badge-gray',
}
function roleBadge(r) { return ROLE_BADGE[r] ?? 'badge-gray' }

function formatDate(d) {
  if (!d) return '-'
  return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>
