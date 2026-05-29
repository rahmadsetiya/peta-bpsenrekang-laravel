<template>
  <AppLayout title="Desa">
    <DataTable :items="items.data" :col-count="5" v-model="search">
      <template #actions>
        <Link :href="route('desa.create')" class="btn btn-primary">
          <i class="fas fa-plus" style="font-size:11px"></i> Tambah
        </Link>
        <a :href="route('desa.export')" class="btn btn-success">
          <i class="fas fa-file-excel" style="font-size:11px"></i> Export
        </a>
        <button @click="showImport = true" class="btn btn-warning">
          <i class="fas fa-upload" style="font-size:11px"></i> Import
        </button>
      </template>

      <template #headers>
        <th class="table-th">Kode Desa</th>
        <th class="table-th">Nama Desa</th>
        <th class="table-th">Kecamatan</th>
      </template>
      <template #row="{ row }">
        <td class="table-td font-mono text-xs text-gray-600">{{ row.kode_desa }}</td>
        <td class="table-td font-medium">{{ row.nama_desa }}</td>
        <td class="table-td-muted">{{ row.nama_kecamatan }}</td>
      </template>
      <template #rowActions="{ row }">
        <Link :href="route('desa.detail', row.id)" class="btn-icon btn-icon-info" title="Detail">
          <i class="fas fa-eye text-xs"></i>
        </Link>
        <Link :href="route('desa.edit', row.id)" class="btn-icon btn-icon-edit" title="Edit">
          <i class="fas fa-pencil-alt text-xs"></i>
        </Link>
        <button @click="confirmDelete(row)" class="btn-icon btn-icon-danger" title="Hapus">
          <i class="fas fa-trash text-xs"></i>
        </button>
      </template>
    </DataTable>

    <Pagination :links="items.links" :from="items.from" :to="items.to" :total="items.total" />

    <!-- Import modal -->
    <Teleport to="body">
      <div v-if="showImport" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showImport = false" />
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900">Import Excel Desa</h3>
            <button @click="showImport = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1">
              <i class="fas fa-times text-sm"></i>
            </button>
          </div>
          <form @submit.prevent="doImport">
            <div class="p-6">
              <label class="form-label">File Excel (.xlsx, .xls)</label>
              <input
                type="file"
                @change="importFile = $event.target.files[0]"
                accept=".xlsx,.xls"
                required
                class="w-full text-sm text-gray-500
                       file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                       file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
                       hover:file:bg-indigo-100 cursor-pointer"
              />
            </div>
            <div class="flex justify-end gap-3 px-6 pb-6">
              <button type="button" @click="showImport = false" class="btn btn-secondary">Batal</button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload" style="font-size:11px"></i> Import
              </button>
            </div>
          </form>
        </div>
      </div>
    </Teleport>

    <DeleteModal v-if="deleteTarget" :name="deleteTarget.kode_desa"
      @confirm="doDelete" @cancel="deleteTarget = null" />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import DeleteModal from '@/Components/DeleteModal.vue'
import Pagination from '@/Components/Pagination.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
  items: Object,
  filters: Object,
})

const deleteTarget = ref(null)
const showImport = ref(false)
const importFile = ref(null)
const search = ref(props.filters?.search ?? '')

let searchTimer = null
watch(search, val => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    router.get(route('desa.index'), { search: val }, { preserveState: true, replace: true })
  }, 350)
})

function confirmDelete(row) { deleteTarget.value = row }
function doDelete() {
  router.delete(route('desa.destroy', deleteTarget.value.id), {
    onFinish: () => { deleteTarget.value = null },
  })
}
function doImport() {
  if (!importFile.value) return
  const data = new FormData()
  data.append('file', importFile.value)
  router.post(route('desa.import'), data, {
    onFinish: () => { showImport.value = false },
  })
}
</script>
