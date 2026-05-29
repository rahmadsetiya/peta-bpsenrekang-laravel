<template>
  <AppLayout title="Opsi Kegiatan">
    <DataTable :items="items" :col-count="3">
      <template #actions>
        <Link :href="route('opsi-kegiatan.create')" class="btn btn-primary">
          <i class="fas fa-plus" style="font-size:11px"></i> Tambah
        </Link>
      </template>
      <template #headers>
        <th class="table-th">Kode Kegiatan</th>
        <th class="table-th">Nama Kegiatan</th>
      </template>
      <template #row="{ row }">
        <td class="table-td font-mono text-xs text-gray-600">{{ row.kode_kegiatan }}</td>
        <td class="table-td font-medium">{{ row.nama_kegiatan }}</td>
      </template>
      <template #rowActions="{ row }">
        <Link :href="route('opsi-kegiatan.edit', row.id)" class="btn-icon btn-icon-edit" title="Edit">
          <i class="fas fa-pencil-alt text-xs"></i>
        </Link>
        <button @click="confirmDelete(row)" class="btn-icon btn-icon-danger" title="Hapus">
          <i class="fas fa-trash text-xs"></i>
        </button>
      </template>
    </DataTable>

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
    onFinish: () => { deleteTarget.value = null },
  })
}
</script>
