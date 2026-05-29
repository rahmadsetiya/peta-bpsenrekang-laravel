<template>
  <AppLayout :title="item ? 'Edit SLS' : 'Tambah SLS'">
    <div class="card">
      <div class="card-header"><h3 class="card-title">{{ item ? 'Edit' : 'Tambah' }} SLS</h3></div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Kode SLS <span class="text-danger">*</span></label>
                <input v-model="form.kode_sls" type="text" class="form-control" :class="{ 'is-invalid': form.errors.kode_sls }">
                <div class="invalid-feedback">{{ form.errors.kode_sls }}</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama SLS <span class="text-danger">*</span></label>
                <input v-model="form.nama_sls" type="text" class="form-control" :class="{ 'is-invalid': form.errors.nama_sls }">
                <div class="invalid-feedback">{{ form.errors.nama_sls }}</div>
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
            <div class="col-md-6">
              <div class="form-group">
                <label>Kode Desa</label>
                <input v-model="form.kode_desa" type="text" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Desa</label>
                <input v-model="form.nama_desa" type="text" class="form-control">
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary mr-2" :disabled="form.processing">Simpan</button>
          <Link :href="route('sls.index')" class="btn btn-secondary">Batal</Link>
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
