<template>
  <AppLayout title="SLS">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar SLS</h3>
        <div>
          <Link :href="route('sls.create')" class="btn btn-primary btn-sm mr-1">
            <i class="fas fa-plus"></i> Tambah
          </Link>
          <a :href="route('sls.export')" class="btn btn-success btn-sm mr-1">
            <i class="fas fa-file-excel"></i> Export
          </a>
          <button @click="showImport = true" class="btn btn-warning btn-sm">
            <i class="fas fa-file-import"></i> Import
          </button>
        </div>
      </div>
      <div class="card-body">
        <DataTable :items="items" :col-count="4">
          <template #headers>
            <th>Kode SLS</th>
            <th>Nama SLS</th>
            <th>Nama Desa</th>
          </template>
          <template #row="{ row }">
            <td>{{ row.kode_sls }}</td>
            <td>{{ row.nama_sls }}</td>
            <td>{{ row.nama_desa }}</td>
          </template>
          <template #rowActions="{ row }">
            <Link :href="route('sls.detail', row.id)" class="btn btn-sm btn-info mr-1"><i class="fas fa-eye"></i></Link>
            <Link :href="route('sls.edit', row.id)" class="btn btn-sm btn-warning mr-1"><i class="fas fa-edit"></i></Link>
            <button @click="confirmDelete(row)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
          </template>
        </DataTable>
      </div>
    </div>

    <div v-if="showImport" class="modal fade show d-block" style="background:rgba(0,0,0,.5)">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Import Excel SLS</h5>
            <button @click="showImport = false" class="close"><span>&times;</span></button>
          </div>
          <form @submit.prevent="doImport" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="file" @change="importFile = $event.target.files[0]" accept=".xlsx,.xls" class="form-control-file" required>
            </div>
            <div class="modal-footer">
              <button type="button" @click="showImport = false" class="btn btn-secondary">Batal</button>
              <button type="submit" class="btn btn-primary">Import</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <DeleteModal v-if="deleteTarget" :name="deleteTarget.kode_sls"
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
const showImport = ref(false)
const importFile = ref(null)

function confirmDelete(row) { deleteTarget.value = row }
function doDelete() {
  router.delete(route('sls.destroy', deleteTarget.value.id), {
    onFinish: () => { deleteTarget.value = null }
  })
}
function doImport() {
  if (!importFile.value) return
  const data = new FormData()
  data.append('file', importFile.value)
  router.post(route('sls.import'), data, {
    onFinish: () => { showImport.value = false }
  })
}
</script>
