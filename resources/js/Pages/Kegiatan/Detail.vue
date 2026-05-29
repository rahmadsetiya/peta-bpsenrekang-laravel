<template>
  <AppLayout :title="`Detail Kegiatan`">
    <div class="row">
      <div class="col-md-4">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Info Kegiatan</h3>
            <div class="card-tools">
              <Link :href="route('kelola-peta.index', kegiatan.id)" class="btn btn-sm btn-warning">
                <i class="fas fa-map"></i> Kelola Peta
              </Link>
            </div>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-5">Kegiatan</dt>
              <dd class="col-sm-7">{{ kegiatan.opsi_kegiatan?.nama_kegiatan }}</dd>
              <dt class="col-sm-5">Tahun</dt><dd class="col-sm-7">{{ kegiatan.tahun }}</dd>
              <dt class="col-sm-5">Bulan</dt><dd class="col-sm-7">{{ kegiatan.bulan }}</dd>
              <dt class="col-sm-5">Batas Cetak</dt><dd class="col-sm-7">{{ kegiatan.tanggal_batas_cetak }}</dd>
              <dt class="col-sm-5">Status</dt>
              <dd class="col-sm-7">
                <span class="badge" :class="statusBadge(kegiatan.status)">{{ kegiatan.status }}</span>
              </dd>
              <dt class="col-sm-5">Dibuat Oleh</dt><dd class="col-sm-7">{{ kegiatan.user?.username }}</dd>
            </dl>
          </div>
        </div>

        <!-- Status Timeline -->
        <div class="card">
          <div class="card-header"><h3 class="card-title">Alur Status</h3></div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              <li v-for="s in statuses" :key="s" class="list-group-item d-flex align-items-center">
                <i class="fas mr-2" :class="kegiatan.status === s ? 'fa-check-circle text-success' : 'fa-circle text-muted'"></i>
                {{ s }}
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <!-- Wilkerstat -->
        <div class="card">
          <div class="card-header"><h3 class="card-title">Blok Sensus ({{ kegiatan.blok_sensus?.length }})</h3></div>
          <div class="card-body p-0" style="max-height:200px;overflow-y:auto">
            <table class="table table-sm mb-0">
              <tbody>
                <tr v-if="!kegiatan.blok_sensus?.length"><td class="text-muted text-center">Tidak ada</td></tr>
                <tr v-for="bs in kegiatan.blok_sensus" :key="bs.id">
                  <td>{{ bs.kode_bs }} - {{ bs.nama_bs }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><h3 class="card-title">SLS ({{ kegiatan.sls?.length }})</h3></div>
          <div class="card-body p-0" style="max-height:200px;overflow-y:auto">
            <table class="table table-sm mb-0">
              <tbody>
                <tr v-if="!kegiatan.sls?.length"><td class="text-muted text-center">Tidak ada</td></tr>
                <tr v-for="s in kegiatan.sls" :key="s.id">
                  <td>{{ s.kode_sls }} - {{ s.nama_sls }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><h3 class="card-title">Desa ({{ kegiatan.desa?.length }})</h3></div>
          <div class="card-body p-0" style="max-height:200px;overflow-y:auto">
            <table class="table table-sm mb-0">
              <tbody>
                <tr v-if="!kegiatan.desa?.length"><td class="text-muted text-center">Tidak ada</td></tr>
                <tr v-for="d in kegiatan.desa" :key="d.id">
                  <td>{{ d.kode_desa }} - {{ d.nama_desa }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Peta -->
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Peta Wilkerstat ({{ kegiatan.wilkerstat_peta?.length }})</h3>
            <Link :href="route('kelola-peta.index', kegiatan.id)" class="btn btn-sm btn-primary">
              <i class="fas fa-map"></i> Kelola
            </Link>
          </div>
          <div class="card-body">
            <div v-if="!kegiatan.wilkerstat_peta?.length" class="text-muted">Belum ada peta diupload</div>
            <div class="row">
              <div v-for="p in mainPeta" :key="p.id" class="col-md-4 mb-2">
                <div class="card card-outline card-secondary">
                  <div class="card-body p-2">
                    <a :href="route('preview-peta', p.file_path)" target="_blank">
                      <img :src="route('preview-peta', p.file_path)" class="img-fluid rounded">
                    </a>
                    <p class="small mt-1 mb-0 text-truncate" :title="p.nama_file">{{ p.nama_file }}</p>
                    <small class="text-muted">{{ p.wilkerstat_type }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Link :href="route('kegiatan.index')" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </Link>
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
