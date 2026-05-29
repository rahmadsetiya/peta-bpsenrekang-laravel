<template>
  <AppLayout title="Dashboard">
    <!-- Stat cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
      <div class="card p-5">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kegiatan</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_kegiatan }}</p>
          </div>
          <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-tasks text-indigo-600"></i>
          </div>
        </div>
        <Link :href="route('kegiatan.index')" class="mt-3 inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
          Lihat semua <i class="fas fa-arrow-right" style="font-size:9px"></i>
        </Link>
      </div>

      <div class="card p-5">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Peta</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_peta }}</p>
          </div>
          <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-map text-emerald-600"></i>
          </div>
        </div>
        <span class="mt-3 inline-block text-xs text-gray-400">File peta terupload</span>
      </div>

      <div class="card p-5">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Blok Sensus</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_blok_sensus }}</p>
          </div>
          <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-th-large text-amber-600"></i>
          </div>
        </div>
        <span class="mt-3 inline-block text-xs text-gray-400">Total blok sensus</span>
      </div>

      <div class="card p-5">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">SLS</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_sls }}</p>
          </div>
          <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center shrink-0">
            <i class="fas fa-layer-group text-sky-600"></i>
          </div>
        </div>
        <span class="mt-3 inline-block text-xs text-gray-400">Total SLS</span>
      </div>
    </div>

    <!-- Bottom section -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
      <!-- Status table -->
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">Status Kegiatan</h2>
        </div>
        <div class="overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr class="bg-gray-50">
                <th class="table-th">Status</th>
                <th class="table-th text-right">Jumlah</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
              <tr v-if="!Object.keys(statusCounts).length">
                <td colspan="2" class="px-4 py-8 text-center text-gray-400 text-sm">Belum ada data</td>
              </tr>
              <tr v-for="(count, status) in statusCounts" :key="status" class="hover:bg-gray-50/60 transition-colors">
                <td class="table-td">
                  <span :class="['badge', statusBadge(status)]">{{ status }}</span>
                </td>
                <td class="table-td text-right font-semibold text-gray-900">{{ count }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Summary -->
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">Ringkasan Data</h2>
        </div>
        <div class="card-body">
          <dl class="space-y-1">
            <div v-for="(val, label) in summary" :key="label" class="flex justify-between items-center py-2.5 border-b border-gray-50 last:border-0">
              <dt class="text-sm text-gray-500">{{ label }}</dt>
              <dd class="text-sm font-semibold text-gray-900">{{ val }}</dd>
            </div>
          </dl>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  stats: Object,
  statusCounts: Object,
})

const page = usePage()

const summary = computed(() => ({
  'Total Desa': props.stats.total_desa,
  'Total SLS': props.stats.total_sls,
  'Total Blok Sensus': props.stats.total_blok_sensus,
  'Total Users': props.stats.total_users,
}))

const STATUS_BADGE = {
  'disiapkan (IPDS)': 'badge-gray',
  'digunakan': 'badge-indigo',
  'scan peta': 'badge-amber',
  'upload peta': 'badge-sky',
  'selesai': 'badge-emerald',
}

function statusBadge(status) {
  return STATUS_BADGE[status] ?? 'badge-gray'
}
</script>
