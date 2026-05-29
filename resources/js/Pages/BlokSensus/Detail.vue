<template>
  <AppLayout :title="`Detail Blok Sensus: ${item.kode_bs}`">
    <div class="flex items-center gap-2 mb-5">
      <Link :href="route('blok-sensus.index')" class="btn btn-secondary">
        <i class="fas fa-arrow-left" style="font-size:11px"></i> Kembali
      </Link>
      <Link :href="route('blok-sensus.edit', item.id)" class="btn btn-warning">
        <i class="fas fa-pencil-alt" style="font-size:11px"></i> Edit
      </Link>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">Info Blok Sensus</h2>
        </div>
        <div class="divide-y divide-gray-50">
          <div v-for="row in infoRows" :key="row.label" class="flex justify-between items-center px-6 py-3">
            <span class="text-xs text-gray-500">{{ row.label }}</span>
            <span :class="['text-sm font-medium text-gray-900', row.mono && 'font-mono']">{{ row.value ?? '-' }}</span>
          </div>
        </div>
      </div>

      <div class="xl:col-span-2 space-y-4">
        <!-- Kegiatan -->
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Kegiatan Terkait</h2>
            <span class="badge badge-gray">{{ kegiatan.length }}</span>
          </div>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr class="bg-gray-50">
                  <th class="table-th">Kegiatan</th>
                  <th class="table-th">Tahun</th>
                  <th class="table-th">Bulan</th>
                  <th class="table-th">Status</th>
                  <th class="table-th text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 bg-white">
                <tr v-if="!kegiatan.length">
                  <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">Belum ada kegiatan</td>
                </tr>
                <tr v-for="k in kegiatan" :key="k.id" class="hover:bg-gray-50/60 transition-colors">
                  <td class="table-td">{{ k.opsi_kegiatan?.nama_kegiatan }}</td>
                  <td class="table-td-muted">{{ k.tahun }}</td>
                  <td class="table-td-muted">{{ k.bulan }}</td>
                  <td class="table-td">
                    <span :class="['badge', statusBadge(k.status)]">{{ k.status }}</span>
                  </td>
                  <td class="px-4 py-3 text-right">
                    <Link :href="route('kegiatan.detail', k.id)" class="btn-icon btn-icon-info" title="Detail">
                      <i class="fas fa-eye text-xs"></i>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Peta -->
        <div v-if="peta && peta.length" class="card">
          <div class="card-header">
            <h2 class="card-title">Peta Terbaru</h2>
            <span class="badge badge-gray">{{ peta.length }}</span>
          </div>
          <div class="card-body">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
              <div v-for="p in peta" :key="p.id" class="group relative overflow-hidden rounded-lg border border-gray-200">
                <a :href="route('preview-peta', p.file_path)" target="_blank">
                  <img :src="route('preview-peta', p.file_path)" class="w-full aspect-square object-cover" />
                </a>
                <div class="p-2">
                  <p class="text-xs text-gray-700 font-medium truncate" :title="p.nama_file">{{ p.nama_file }}</p>
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

const props = defineProps({ item: Object, kegiatan: Array, peta: Array })

const infoRows = computed(() => [
  { label: 'Kode BS', value: props.item.kode_bs, mono: true },
  { label: 'Nama BS', value: props.item.nama_bs },
  { label: 'Luas', value: props.item.luas },
  { label: 'Desa', value: props.item.nama_desa },
  { label: 'Kecamatan', value: props.item.nama_kecamatan },
])

const STATUS_BADGE = {
  'disiapkan (IPDS)': 'badge-gray',
  'digunakan': 'badge-indigo',
  'scan peta': 'badge-amber',
  'upload peta': 'badge-sky',
  'selesai': 'badge-emerald',
}
function statusBadge(s) { return STATUS_BADGE[s] ?? 'badge-gray' }
</script>
