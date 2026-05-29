<template>
  <AppLayout :title="`Detail SLS: ${item.kode_sls}`">
    <div class="row">
      <div class="col-md-4">
        <div class="card card-primary">
          <div class="card-header"><h3 class="card-title">Info SLS</h3></div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-5">Kode SLS</dt><dd class="col-sm-7">{{ item.kode_sls }}</dd>
              <dt class="col-sm-5">Nama SLS</dt><dd class="col-sm-7">{{ item.nama_sls }}</dd>
              <dt class="col-sm-5">Luas</dt><dd class="col-sm-7">{{ item.luas }}</dd>
              <dt class="col-sm-5">Desa</dt><dd class="col-sm-7">{{ item.nama_desa }}</dd>
              <dt class="col-sm-5">Kecamatan</dt><dd class="col-sm-7">{{ item.nama_kecamatan }}</dd>
            </dl>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header"><h3 class="card-title">Kegiatan Terkait</h3></div>
          <div class="card-body p-0">
            <table class="table table-bordered">
              <thead><tr><th>Kegiatan</th><th>Tahun</th><th>Bulan</th><th>Status</th><th>Aksi</th></tr></thead>
              <tbody>
                <tr v-if="!kegiatan.length"><td colspan="5" class="text-center text-muted">Belum ada kegiatan</td></tr>
                <tr v-for="k in kegiatan" :key="k.id">
                  <td>{{ k.opsi_kegiatan?.nama_kegiatan }}</td>
                  <td>{{ k.tahun }}</td>
                  <td>{{ k.bulan }}</td>
                  <td><span class="badge badge-info">{{ k.status }}</span></td>
                  <td><Link :href="route('kegiatan.detail', k.id)" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></Link></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <Link :href="route('sls.index')" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</Link>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
defineProps({ item: Object, kegiatan: Array, peta: Array })
</script>
