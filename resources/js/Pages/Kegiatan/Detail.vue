<template>
  <AppLayout title="Detail Kegiatan">
    <div class="flex items-center gap-2 mb-5">
      <Link :href="route('kegiatan.index')" class="btn btn-secondary">
        <i class="fas fa-arrow-left" style="font-size:11px"></i> Kembali
      </Link>
      <Link :href="route('kelola-peta.index', kegiatan.id)" class="btn btn-warning">
        <i class="fas fa-map" style="font-size:11px"></i> Kelola Peta
      </Link>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
      <!-- Left column -->
      <div class="space-y-4">
        <!-- Info -->
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Info Kegiatan</h2>
            <span :class="['badge', statusBadge(kegiatan.status)]">{{ kegiatan.status }}</span>
          </div>
          <div class="divide-y divide-gray-50">
            <div v-for="row in infoRows" :key="row.label" class="flex justify-between items-center px-6 py-3">
              <span class="text-xs text-gray-500">{{ row.label }}</span>
              <span class="text-sm font-medium text-gray-900">{{ row.value ?? '-' }}</span>
            </div>
          </div>
        </div>

        <!-- Status timeline -->
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Alur Status</h2>
          </div>
          <div class="p-4">
            <ol class="relative space-y-3">
              <li v-for="s in statuses" :key="s" class="flex items-center gap-3">
                <div :class="[
                  'w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-xs',
                  kegiatan.status === s
                    ? 'bg-indigo-600 text-white ring-2 ring-indigo-200'
                    : statusIndex(s) < statusIndex(kegiatan.status)
                      ? 'bg-emerald-500 text-white'
                      : 'bg-gray-200 text-gray-400'
                ]">
                  <i v-if="statusIndex(s) < statusIndex(kegiatan.status)" class="fas fa-check" style="font-size:8px"></i>
                  <i v-else-if="kegiatan.status === s" class="fas fa-circle" style="font-size:6px"></i>
                </div>
                <span :class="[
                  'text-xs',
                  kegiatan.status === s ? 'text-indigo-700 font-semibold' : 'text-gray-500'
                ]">{{ s }}</span>
              </li>
            </ol>
          </div>
        </div>
      </div>

      <!-- Right column -->
      <div class="xl:col-span-2 space-y-4">
        <!-- Wilkerstat counts -->
        <div class="grid grid-cols-3 gap-3">
          <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ kegiatan.blok_sensus?.length ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Blok Sensus</p>
          </div>
          <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ kegiatan.sls?.length ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">SLS</p>
          </div>
          <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ kegiatan.desa?.length ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Desa</p>
          </div>
        </div>

        <!-- Peta gallery -->
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Peta Terupload</h2>
            <div class="flex items-center gap-2">
              <span class="badge badge-gray">{{ mainPeta.length }}</span>
              <Link :href="route('kelola-peta.index', kegiatan.id)" class="btn btn-primary" style="padding:4px 12px;font-size:12px">
                <i class="fas fa-map" style="font-size:10px"></i> Kelola
              </Link>
            </div>
          </div>
          <div class="card-body">
            <div v-if="!mainPeta.length" class="flex flex-col items-center gap-2 py-6 text-gray-400">
              <i class="fas fa-images text-3xl"></i>
              <p class="text-sm">Belum ada peta diupload</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <div v-for="p in mainPeta" :key="p.id" class="rounded-xl overflow-hidden border border-gray-200">
                <a :href="route('preview-peta', p.file_path)" target="_blank">
                  <img :src="route('preview-peta', p.file_path)" class="w-full aspect-video object-cover hover:opacity-90 transition-opacity" />
                </a>
                <div class="p-2">
                  <p class="text-xs text-gray-700 font-medium truncate" :title="p.nama_file">{{ p.nama_file }}</p>
                  <p class="text-xs text-gray-400 mt-0.5">{{ p.wilkerstat_type }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({ kegiatan: Object, statuses: Array })

const mainPeta = computed(() =>
  (props.kegiatan.wilkerstat_peta ?? []).filter(p => !p.id_parent_peta)
)

const infoRows = computed(() => [
  { label: 'Kegiatan', value: props.kegiatan.opsi_kegiatan?.nama_kegiatan },
  { label: 'Tahun', value: props.kegiatan.tahun },
  { label: 'Bulan', value: props.kegiatan.bulan },
  { label: 'Batas Cetak', value: formatDate(props.kegiatan.tanggal_batas_cetak) },
  { label: 'Dibuat Oleh', value: props.kegiatan.user?.username },
])

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

function statusIndex(s) {
  return props.statuses?.indexOf(s) ?? -1
}
</script>
