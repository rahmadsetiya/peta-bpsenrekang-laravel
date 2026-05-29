<template>
  <AppLayout :title="item ? 'Edit Desa' : 'Tambah Desa'">
    <div class="card">
      <div class="card-header"><h3 class="card-title">{{ item ? 'Edit' : 'Tambah' }} Desa</h3></div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Kode Desa <span class="text-danger">*</span></label>
                <input v-model="form.kode_desa" type="text" class="form-control" :class="{ 'is-invalid': form.errors.kode_desa }">
                <div class="invalid-feedback">{{ form.errors.kode_desa }}</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Desa <span class="text-danger">*</span></label>
                <input v-model="form.nama_desa" type="text" class="form-control" :class="{ 'is-invalid': form.errors.nama_desa }">
                <div class="invalid-feedback">{{ form.errors.nama_desa }}</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Luas</label>
                <input v-model="form.luas" type="number" step="0.01" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Kode Provinsi</label>
                <input v-model="form.kode_prov" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Provinsi</label>
                <input v-model="form.nama_provinsi" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Kode Kabupaten</label>
                <input v-model="form.kode_kabupaten" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Kabupaten</label>
                <input v-model="form.nama_kabupaten" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Kode Kecamatan</label>
                <input v-model="form.kode_kecamatan" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Kecamatan</label>
                <input v-model="form.nama_kecamatan" type="text" class="form-control">
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary mr-2" :disabled="form.processing">Simpan</button>
          <Link :href="route('desa.index')" class="btn btn-secondary">Batal</Link>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({ item: Object })

const form = useForm({
  kode_desa: props.item?.kode_desa ?? '',
  nama_desa: props.item?.nama_desa ?? '',
  luas: props.item?.luas ?? '',
  kode_prov: props.item?.kode_prov ?? '',
  nama_provinsi: props.item?.nama_provinsi ?? '',
  kode_kabupaten: props.item?.kode_kabupaten ?? '',
  nama_kabupaten: props.item?.nama_kabupaten ?? '',
  kode_kecamatan: props.item?.kode_kecamatan ?? '',
  nama_kecamatan: props.item?.nama_kecamatan ?? '',
})

function submit() {
  props.item
    ? form.put(route('desa.update', props.item.id))
    : form.post(route('desa.store'))
}
</script>
