<template>
  <AppLayout :title="item ? 'Edit SLS' : 'Tambah SLS'">
    <div class="max-w-3xl">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">{{ item ? 'Edit' : 'Tambah' }} SLS</h2>
        </div>
        <div class="card-body">
          <form @submit.prevent="submit" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="form-label">Kode SLS <span class="text-red-500">*</span></label>
                <input v-model="form.kode_sls" type="text" class="form-field" :class="{ 'form-field-error': form.errors.kode_sls }" />
                <p v-if="form.errors.kode_sls" class="form-error">{{ form.errors.kode_sls }}</p>
              </div>
              <div>
                <label class="form-label">Nama SLS <span class="text-red-500">*</span></label>
                <input v-model="form.nama_sls" type="text" class="form-field" :class="{ 'form-field-error': form.errors.nama_sls }" />
                <p v-if="form.errors.nama_sls" class="form-error">{{ form.errors.nama_sls }}</p>
              </div>
              <div>
                <label class="form-label">Luas</label>
                <input v-model="form.luas" type="number" step="0.01" class="form-field" />
              </div>
              <div>
                <label class="form-label">Kode Provinsi</label>
                <input v-model="form.kode_prov" type="text" class="form-field" />
              </div>
              <div>
                <label class="form-label">Nama Provinsi</label>
                <input v-model="form.nama_provinsi" type="text" class="form-field" />
              </div>
              <div>
                <label class="form-label">Kode Kabupaten</label>
                <input v-model="form.kode_kabupaten" type="text" class="form-field" />
              </div>
              <div>
                <label class="form-label">Nama Kabupaten</label>
                <input v-model="form.nama_kabupaten" type="text" class="form-field" />
              </div>
              <div>
                <label class="form-label">Kode Kecamatan</label>
                <input v-model="form.kode_kecamatan" type="text" class="form-field" />
              </div>
              <div>
                <label class="form-label">Nama Kecamatan</label>
                <input v-model="form.nama_kecamatan" type="text" class="form-field" />
              </div>
              <div>
                <label class="form-label">Kode Desa</label>
                <input v-model="form.kode_desa" type="text" class="form-field" />
              </div>
              <div>
                <label class="form-label">Nama Desa</label>
                <input v-model="form.nama_desa" type="text" class="form-field" />
              </div>
            </div>
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
              <button type="submit" :disabled="form.processing" class="btn btn-primary">
                <i v-if="form.processing" class="fas fa-spinner fa-spin text-xs"></i>
                <span v-else>Simpan</span>
              </button>
              <Link :href="route('sls.index')" class="btn btn-secondary">Batal</Link>
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

const props = defineProps({ item: Object })

const form = useForm({
  kode_sls: props.item?.kode_sls ?? '',
  nama_sls: props.item?.nama_sls ?? '',
  luas: props.item?.luas ?? '',
  kode_prov: props.item?.kode_prov ?? '',
  nama_provinsi: props.item?.nama_provinsi ?? '',
  kode_kabupaten: props.item?.kode_kabupaten ?? '',
  nama_kabupaten: props.item?.nama_kabupaten ?? '',
  kode_kecamatan: props.item?.kode_kecamatan ?? '',
  nama_kecamatan: props.item?.nama_kecamatan ?? '',
  kode_desa: props.item?.kode_desa ?? '',
  nama_desa: props.item?.nama_desa ?? '',
})

function submit() {
  props.item
    ? form.put(route('sls.update', props.item.id))
    : form.post(route('sls.store'))
}
</script>
