<template>
  <AppLayout title="Kegiatan">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Kegiatan</h3>
        <Link v-if="canCreate" :href="route('kegiatan.create')" class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah
        </Link>
      </div>
      <div class="card-body">
        <div class="mb-3 d-flex gap-2 flex-wrap">
          <button
            v-for="s in ['semua', ...statuses]" :key="s"
            @click="filterStatus = s === 'semua' ? '' : s"
            class="btn btn-sm"
            :class="(filterStatus === '' && s === 'semua') || filterStatus === s ? 'btn-primary' : 'btn-outline-secondary'"
          >{{ s }}</button>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>Kegiatan</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Batas Cetak</th>
                <th>Status</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="!filtered.length">
                <td colspan="8" class="text-center text-muted">Tidak ada data</td>
              </tr>
              <tr v-for="(k, i) in filtered" :key="k.id">
                <td>{{ i + 1 }}</td>
                <td>{{ k.opsi_kegiatan?.nama_kegiatan }}</td>
                <td>{{ k.tahun }}</td>
                <td>{{ k.bulan }}</td>
                <td>{{ k.tanggal_batas_cetak }}</td>
                <td><span class="badge" :class="statusBadge(k.status)">{{ k.status }}</span></td>
                <td>{{ k.user?.username }}</td>
                <td>
                  <Link :href="route('kegiatan.detail', k.id)" class="btn btn-sm btn-info mr-1">
                    <i class="fas fa-eye"></i>
                  </Link>
                  <Link v-if="canEdit(k)" :href="route('kegiatan.edit', k.id)" class="btn btn-sm btn-warning mr-1">
                    <i class="fas fa-edit"></i>
                  </Link>
                  <button v-if="canDelete" @click="confirmDelete(k)" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
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
    onFinish: () => { deleteTarget.value = null }
  })
}

function statusBadge(status) {
  const map = {
    'disiapkan (IPDS)': 'badge-secondary',
    'digunakan': 'badge-primary',
    'scan peta': 'badge-warning',
    'upload peta': 'badge-info',
    'selesai': 'badge-success',
  }
  return map[status] ?? 'badge-light'
}
</script>
