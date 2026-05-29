<template>
  <AppLayout :title="kegiatan ? 'Edit Kegiatan' : 'Tambah Kegiatan'">
    <div class="max-w-4xl space-y-4">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">{{ kegiatan ? 'Edit' : 'Tambah' }} Kegiatan</h2>
        </div>
        <div class="card-body">
          <form @submit.prevent="submit" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              <div class="sm:col-span-2 lg:col-span-3">
                <label class="form-label">Opsi Kegiatan <span class="text-red-500">*</span></label>
                <select v-model="form.id_opsi_kegiatan" class="form-field" :class="{ 'form-field-error': form.errors.id_opsi_kegiatan }">
                  <option value="">-- Pilih --</option>
                  <option v-for="o in opsiKegiatan" :key="o.id" :value="o.id">
                    {{ o.kode_kegiatan }} - {{ o.nama_kegiatan }}
                  </option>
                </select>
                <p v-if="form.errors.id_opsi_kegiatan" class="form-error">{{ form.errors.id_opsi_kegiatan }}</p>
              </div>

              <div>
                <label class="form-label">Tahun <span class="text-red-500">*</span></label>
                <input v-model="form.tahun" type="number" class="form-field" :class="{ 'form-field-error': form.errors.tahun }" />
                <p v-if="form.errors.tahun" class="form-error">{{ form.errors.tahun }}</p>
              </div>

              <div>
                <label class="form-label">Bulan <span class="text-red-500">*</span></label>
                <select v-model="form.bulan" class="form-field" :class="{ 'form-field-error': form.errors.bulan }">
                  <option value="">-- Pilih --</option>
                  <option v-for="b in bulanList" :key="b" :value="b">{{ b }}</option>
                </select>
                <p v-if="form.errors.bulan" class="form-error">{{ form.errors.bulan }}</p>
              </div>

              <div>
                <label class="form-label">Batas Cetak</label>
                <input v-model="form.tanggal_batas_cetak" type="date" class="form-field" />
              </div>

              <div v-if="canChangeStatus">
                <label class="form-label">Status</label>
                <select v-model="form.status" class="form-field">
                  <option v-for="s in statuses" :key="s" :value="s">{{ s }}</option>
                </select>
              </div>
            </div>

            <!-- Selection panels -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 pt-2">
              <!-- Blok Sensus -->
              <div class="card border-0 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                  <h3 class="text-sm font-semibold text-gray-700">
                    Blok Sensus
                    <span class="badge badge-indigo ml-1">{{ form.blok_sensus_ids.length }}</span>
                  </h3>
                  <div class="flex gap-1">
                    <button type="button" @click="selectAll('blok_sensus_ids', blokSensus)"
                      class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Semua</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" @click="form.blok_sensus_ids = []"
                      class="text-xs text-gray-500 hover:text-gray-700">Hapus</button>
                  </div>
                </div>
                <div class="overflow-y-auto p-3 space-y-1" style="max-height:180px">
                  <label v-for="bs in blokSensus" :key="bs.id"
                    class="flex items-center gap-2 p-1.5 rounded cursor-pointer hover:bg-white transition-colors text-xs">
                    <input type="checkbox" :value="bs.id" v-model="form.blok_sensus_ids"
                      class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5 shrink-0" />
                    <span class="truncate text-gray-700">{{ bs.kode_bs }} - {{ bs.nama_bs }}</span>
                  </label>
                  <p v-if="!blokSensus.length" class="text-xs text-gray-400 text-center py-2">Tidak ada data</p>
                </div>
              </div>

              <!-- SLS -->
              <div class="card border-0 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                  <h3 class="text-sm font-semibold text-gray-700">
                    SLS
                    <span class="badge badge-indigo ml-1">{{ form.sls_ids.length }}</span>
                  </h3>
                  <div class="flex gap-1">
                    <button type="button" @click="selectAll('sls_ids', slsList)"
                      class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Semua</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" @click="form.sls_ids = []"
                      class="text-xs text-gray-500 hover:text-gray-700">Hapus</button>
                  </div>
                </div>
                <div class="overflow-y-auto p-3 space-y-1" style="max-height:180px">
                  <label v-for="s in slsList" :key="s.id"
                    class="flex items-center gap-2 p-1.5 rounded cursor-pointer hover:bg-white transition-colors text-xs">
                    <input type="checkbox" :value="s.id" v-model="form.sls_ids"
                      class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5 shrink-0" />
                    <span class="truncate text-gray-700">{{ s.kode_sls }} - {{ s.nama_sls }}</span>
                  </label>
                  <p v-if="!slsList.length" class="text-xs text-gray-400 text-center py-2">Tidak ada data</p>
                </div>
              </div>

              <!-- Desa -->
              <div class="card border-0 bg-gray-50 rounded-xl">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                  <h3 class="text-sm font-semibold text-gray-700">
                    Desa
                    <span class="badge badge-indigo ml-1">{{ form.desa_ids.length }}</span>
                  </h3>
                  <div class="flex gap-1">
                    <button type="button" @click="selectAll('desa_ids', desaList)"
                      class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Semua</button>
                    <span class="text-gray-300">|</span>
                    <button type="button" @click="form.desa_ids = []"
                      class="text-xs text-gray-500 hover:text-gray-700">Hapus</button>
                  </div>
                </div>
                <div class="overflow-y-auto p-3 space-y-1" style="max-height:180px">
                  <label v-for="d in desaList" :key="d.id"
                    class="flex items-center gap-2 p-1.5 rounded cursor-pointer hover:bg-white transition-colors text-xs">
                    <input type="checkbox" :value="d.id" v-model="form.desa_ids"
                      class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5 shrink-0" />
                    <span class="truncate text-gray-700">{{ d.kode_desa }} - {{ d.nama_desa }}</span>
                  </label>
                  <p v-if="!desaList.length" class="text-xs text-gray-400 text-center py-2">Tidak ada data</p>
                </div>
              </div>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
              <button type="submit" :disabled="form.processing" class="btn btn-primary">
                <i v-if="form.processing" class="fas fa-spinner fa-spin text-xs"></i>
                <span v-else>Simpan</span>
              </button>
              <Link :href="route('kegiatan.index')" class="btn btn-secondary">Batal</Link>
            </div>
          </form>
        </div>
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
