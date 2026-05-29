<template>
  <AppLayout title="Dashboard">
    <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ stats.total_kegiatan }}</h3>
            <p>Total Kegiatan</p>
          </div>
          <div class="icon"><i class="fas fa-tasks"></i></div>
          <Link :href="route('kegiatan.index')" class="small-box-footer">
            Lihat <i class="fas fa-arrow-circle-right"></i>
          </Link>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{ stats.total_peta }}</h3>
            <p>Total Peta</p>
          </div>
          <div class="icon"><i class="fas fa-map"></i></div>
          <span class="small-box-footer">&nbsp;</span>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ stats.total_blok_sensus }}</h3>
            <p>Blok Sensus</p>
          </div>
          <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
          <span class="small-box-footer">&nbsp;</span>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>{{ stats.total_sls }}</h3>
            <p>SLS</p>
          </div>
          <div class="icon"><i class="fas fa-layer-group"></i></div>
          <span class="small-box-footer">&nbsp;</span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Status Kegiatan</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Status</th>
                  <th class="text-right">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(count, status) in statusCounts" :key="status">
                  <td>
                    <span class="badge" :class="statusBadge(status)">{{ status }}</span>
                  </td>
                  <td class="text-right"><strong>{{ count }}</strong></td>
                </tr>
                <tr v-if="!Object.keys(statusCounts).length">
                  <td colspan="2" class="text-center text-muted">Belum ada data</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Ringkasan Data</h3>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-6">Total Desa</dt>
              <dd class="col-sm-6">{{ stats.total_desa }}</dd>
              <dt class="col-sm-6">Total SLS</dt>
              <dd class="col-sm-6">{{ stats.total_sls }}</dd>
              <dt class="col-sm-6">Total Blok Sensus</dt>
              <dd class="col-sm-6">{{ stats.total_blok_sensus }}</dd>
              <dt class="col-sm-6">Total Users</dt>
              <dd class="col-sm-6">{{ stats.total_users }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  stats: Object,
  statusCounts: Object,
  role: String,
})

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
