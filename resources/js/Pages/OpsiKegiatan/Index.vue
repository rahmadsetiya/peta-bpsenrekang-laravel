<template>
  <AppLayout title="Opsi Kegiatan">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Opsi Kegiatan</h3>
        <Link :href="route('opsi-kegiatan.create')" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah
        </Link>
      </div>
      <div class="card-body">
        <DataTable :items="items" :col-count="3">
          <template #headers>
            <th>Kode Kegiatan</th>
            <th>Nama Kegiatan</th>
          </template>
          <template #row="{ row }">
            <td>{{ row.kode_kegiatan }}</td>
            <td>{{ row.nama_kegiatan }}</td>
          </template>
          <template #rowActions="{ row }">
            <Link :href="route('opsi-kegiatan.edit', row.id)" class="btn btn-sm btn-warning mr-1">
              <i class="fas fa-edit"></i>
            </Link>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-danger">
              <i class="fas fa-trash"></i>
            </button>
          </template>
        </DataTable>
      </div>
    </div>

    <DeleteModal v-if="deleteTarget" :name="deleteTarget.nama_kegiatan"
      @confirm="doDelete" @cancel="deleteTarget = null" />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import DeleteModal from '@/Components/DeleteModal.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({ items: Array })
const deleteTarget = ref(null)
function confirmDelete(row) { deleteTarget.value = row }
function doDelete() {
  router.delete(route('opsi-kegiatan.destroy', deleteTarget.value.id), {
    onFinish: () => { deleteTarget.value = null }
  })
}
</script>
