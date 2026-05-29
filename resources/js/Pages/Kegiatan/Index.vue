<template>
  <AppLayout title="Kegiatan">
    <!-- Status filter pills -->
    <div class="flex items-center gap-2 flex-wrap mb-4">
      <button
        v-for="s in ['Semua', ...statuses]"
        :key="s"
        @click="filterStatus = s === 'Semua' ? '' : s"
        :class="[
          'px-3 py-1.5 rounded-full text-xs font-medium transition-colors border',
          (filterStatus === '' && s === 'Semua') || filterStatus === s
            ? 'bg-indigo-600 text-white border-indigo-600'
            : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400 hover:text-indigo-600',
        ]"
      >{{ s }}</button>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="card-title">Daftar Kegiatan</h2>
        <Link v-if="canCreate" :href="route('kegiatan.create')" class="btn btn-primary">
          <i class="fas fa-plus" style="font-size:11px"></i> Tambah
        </Link>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr class="bg-gray-50">
              <th class="table-th w-10">#</th>
              <th class="table-th">Kegiatan</th>
              <th class="table-th">Tahun</th>
              <th class="table-th">Bulan</th>
              <th class="table-th">Batas Cetak</th>
              <th class="table-th">Status</th>
              <th class="table-th">Dibuat Oleh</th>
              <th class="table-th text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            <tr v-if="!filtered.length">
              <td colspan="8" class="px-4 py-12 text-center">
                <div class="flex flex-col items-center gap-2 text-gray-400">
                  <i class="fas fa-inbox text-3xl"></i>
                  <span class="text-sm">Tidak ada data</span>
                </div>
              </td>
            </tr>
            <tr v-for="(k, i) in filtered" :key="k.id" class="hover:bg-gray-50/60 transition-colors">
              <td class="table-td-muted font-mono text-xs">{{ i + 1 }}</td>
              <td class="table-td font-medium">{{ k.opsi_kegiatan?.nama_kegiatan }}</td>
              <td class="table-td-muted">{{ k.tahun }}</td>
              <td class="table-td-muted">{{ k.bulan }}</td>
              <td class="table-td-muted text-xs">{{ formatDate(k.tanggal_batas_cetak) }}</td>
              <td class="table-td">
                <span :class="['badge', statusBadge(k.status)]">{{ k.status }}</span>
              </td>
              <td class="table-td-muted text-xs">{{ k.user?.username }}</td>
              <td class="px-4 py-3 text-right">
                <div class="flex items-center justify-end gap-1">
                  <Link :href="route('kegiatan.detail', k.id)" class="btn-icon btn-icon-info" title="Detail">
                    <i class="fas fa-eye text-xs"></i>
                  </Link>
                  <Link v-if="canEdit(k)" :href="route('kegiatan.edit', k.id)" class="btn-icon btn-icon-edit" title="Edit">
                    <i class="fas fa-pencil-alt text-xs"></i>
                  </Link>
                  <button v-if="canDelete" @click="confirmDelete(k)" class="btn-icon btn-icon-danger" title="Hapus">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <DeleteModal v-if="deleteTarget" :name="`kegiatan ${deleteTarget.opsi_kegiatan?.nama_kegiatan}`"
      @confirm="doDelete" @cancel="deleteTarget = null" />
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DeleteModal from '@/Components/DeleteModal.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({ items: Array, statuses: Array })
const page = usePage()
const user = computed(() => page.props.auth.user)
const role = computed(() => user.value?.role?.toLowerCase())

const canCreate = computed(() => ['admin', 'ipds', 'subject_matter'].includes(role.value))
const canDelete = computed(() => ['admin', 'ipds'].includes(role.value))

function canEdit(k) {
  if (['admin', 'ipds'].includes(role.value)) return true
  if (role.value === 'subject_matter') return k.id_user === user.value?.id
  return false
}

const filterStatus = ref('')
const deleteTarget = ref(null)

const filtered = computed(() => {
  if (!filterStatus.value) return props.items
  return props.items.filter(k => k.status === filterStatus.value)
})

function confirmDelete(k) { deleteTarget.value = k }
function doDelete() {
  router.delete(route('kegiatan.destroy', deleteTarget.value.id), {
    onFinish: () => { deleteTarget.value = null },
  })
}

function formatDate(d) {
  if (!d) return '-'
  return new Date(d + 'T00:00:00').toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

const STATUS_BADGE = {
  'disiapkan (IPDS)': 'badge-gray',
  'digunakan': 'badge-indigo',
  'scan peta': 'badge-amber',
  'upload peta': 'badge-sky',
  'selesai': 'badge-emerald',
}
function statusBadge(s) { return STATUS_BADGE[s] ?? 'badge-gray' }
</script>
