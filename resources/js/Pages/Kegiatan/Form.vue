<template>
  <AppLayout :title="kegiatan ? 'Edit Kegiatan' : 'Tambah Kegiatan'">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ kegiatan ? 'Edit' : 'Tambah' }} Kegiatan</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Opsi Kegiatan <span class="text-danger">*</span></label>
                <select v-model="form.id_opsi_kegiatan" class="form-control" :class="{ 'is-invalid': form.errors.id_opsi_kegiatan }">
                  <option value="">-- Pilih --</option>
                  <option v-for="o in opsiKegiatan" :key="o.id" :value="o.id">
                    {{ o.kode_kegiatan }} - {{ o.nama_kegiatan }}
                  </option>
                </select>
                <div class="invalid-feedback">{{ form.errors.id_opsi_kegiatan }}</div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Tahun <span class="text-danger">*</span></label>
                <input v-model="form.tahun" type="number" class="form-control" :class="{ 'is-invalid': form.errors.tahun }">
                <div class="invalid-feedback">{{ form.errors.tahun }}</div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Bulan <span class="text-danger">*</span></label>
                <select v-model="form.bulan" class="form-control" :class="{ 'is-invalid': form.errors.bulan }">
                  <option value="">-- Pilih --</option>
                  <option v-for="b in bulanList" :key="b" :value="b">{{ b }}</option>
                </select>
                <div class="invalid-feedback">{{ form.errors.bulan }}</div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label>Batas Cetak</label>
                <input v-model="form.tanggal_batas_cetak" type="date" class="form-control">
              </div>
            </div>

            <div v-if="canChangeStatus" class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <select v-model="form.status" class="form-control">
                  <option v-for="s in statuses" :key="s" :value="s">{{ s }}</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Blok Sensus Selection -->
          <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="m-0">Blok Sensus</h5>
              <div>
                <button type="button" @click="selectAll('blok_sensus_ids', blokSensus)" class="btn btn-xs btn-outline-primary mr-1">Pilih Semua</button>
                <button type="button" @click="form.blok_sensus_ids = []" class="btn btn-xs btn-outline-secondary">Hapus Semua</button>
              </div>
            </div>
            <div class="card-body" style="max-height:200px;overflow-y:auto">
              <div class="row">
                <div v-for="bs in blokSensus" :key="bs.id" class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" :id="`bs_${bs.id}`" :value="bs.id" v-model="form.blok_sensus_ids" class="form-check-input">
                    <label :for="`bs_${bs.id}`" class="form-check-label small">{{ bs.kode_bs }} - {{ bs.nama_bs }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- SLS Selection -->
          <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="m-0">SLS</h5>
              <div>
                <button type="button" @click="selectAll('sls_ids', slsList)" class="btn btn-xs btn-outline-primary mr-1">Pilih Semua</button>
                <button type="button" @click="form.sls_ids = []" class="btn btn-xs btn-outline-secondary">Hapus Semua</button>
              </div>
            </div>
            <div class="card-body" style="max-height:200px;overflow-y:auto">
              <div class="row">
                <div v-for="s in slsList" :key="s.id" class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" :id="`sls_${s.id}`" :value="s.id" v-model="form.sls_ids" class="form-check-input">
                    <label :for="`sls_${s.id}`" class="form-check-label small">{{ s.kode_sls }} - {{ s.nama_sls }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Desa Selection -->
          <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="m-0">Desa</h5>
              <div>
                <button type="button" @click="selectAll('desa_ids', desaList)" class="btn btn-xs btn-outline-primary mr-1">Pilih Semua</button>
                <button type="button" @click="form.desa_ids = []" class="btn btn-xs btn-outline-secondary">Hapus Semua</button>
              </div>
            </div>
            <div class="card-body" style="max-height:200px;overflow-y:auto">
              <div class="row">
                <div v-for="d in desaList" :key="d.id" class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" :id="`desa_${d.id}`" :value="d.id" v-model="form.desa_ids" class="form-check-input">
                    <label :for="`desa_${d.id}`" class="form-check-label small">{{ d.kode_desa }} - {{ d.nama_desa }}</label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-primary mr-2" :disabled="form.processing">Simpan</button>
            <Link :href="route('kegiatan.index')" class="btn btn-secondary">Batal</Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  kegiatan: Object,
  opsiKegiatan: Array,
  blokSensus: Array,
  slsList: Array,
  desaList: Array,
  statuses: Array,
  canChangeStatus: Boolean,
})

const bulanList = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
]

const form = useForm({
  id_opsi_kegiatan: props.kegiatan?.id_opsi_kegiatan ?? '',
  tahun: props.kegiatan?.tahun ?? new Date().getFullYear(),
  bulan: props.kegiatan?.bulan ?? '',
  tanggal_batas_cetak: props.kegiatan?.tanggal_batas_cetak ?? '',
  status: props.kegiatan?.status ?? 'disiapkan (IPDS)',
  blok_sensus_ids: props.kegiatan?.blok_sensus?.map(b => b.id) ?? [],
  sls_ids: props.kegiatan?.sls?.map(s => s.id) ?? [],
  desa_ids: props.kegiatan?.desa?.map(d => d.id) ?? [],
})

function selectAll(field, list) {
  form[field] = list.map(i => i.id)
}

function submit() {
  props.kegiatan
    ? form.put(route('kegiatan.update', props.kegiatan.id))
    : form.post(route('kegiatan.store'))
}
</script>
