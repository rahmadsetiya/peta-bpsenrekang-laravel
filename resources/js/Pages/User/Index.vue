<template>
  <AppLayout title="Manajemen User">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar User</h3>
        <Link :href="route('users.create')" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah User
        </Link>
      </div>
      <div class="card-body">
        <DataTable :items="users" :col-count="4">
          <template #headers>
            <th>Username</th>
            <th>Role</th>
            <th>Dibuat</th>
          </template>
          <template #row="{ row }">
            <td>{{ row.username }}</td>
            <td><span class="badge badge-info">{{ row.role }}</span></td>
            <td>{{ formatDate(row.created_at) }}</td>
          </template>
          <template #rowActions="{ row }">
            <Link :href="route('users.edit', row.id)" class="btn btn-sm btn-warning mr-1">
              <i class="fas fa-edit"></i>
            </Link>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-danger">
              <i class="fas fa-trash"></i>
            </button>
          </template>
        </DataTable>
      </div>
    </div>

    <!-- Delete Modal -->
    <div v-if="deleteTarget" class="modal fade show d-block" style="background:rgba(0,0,0,.5)">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Hapus</h5>
          </div>
          <div class="modal-body">
            Hapus user <strong>{{ deleteTarget.username }}</strong>?
          </div>
          <div class="modal-footer">
            <button @click="deleteTarget = null" class="btn btn-secondary">Batal</button>
            <button @click="doDelete" class="btn btn-danger">Hapus</button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({ users: Array })

const deleteTarget = ref(null)

function confirmDelete(row) { deleteTarget.value = row }

function doDelete() {
  router.delete(route('users.destroy', deleteTarget.value.id), {
    onFinish: () => { deleteTarget.value = null }
  })
}

function formatDate(d) {
  if (!d) return '-'
  return new Date(d).toLocaleDateString('id-ID')
}
</script>
