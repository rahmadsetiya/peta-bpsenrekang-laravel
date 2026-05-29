<template>
  <AppLayout :title="item ? 'Edit Opsi Kegiatan' : 'Tambah Opsi Kegiatan'">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ item ? 'Edit' : 'Tambah' }} Opsi Kegiatan</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="form-group">
            <label>Kode Kegiatan</label>
            <input v-model="form.kode_kegiatan" type="text" class="form-control" :class="{ 'is-invalid': form.errors.kode_kegiatan }">
            <div class="invalid-feedback">{{ form.errors.kode_kegiatan }}</div>
          </div>
          <div class="form-group">
            <label>Nama Kegiatan</label>
            <input v-model="form.nama_kegiatan" type="text" class="form-control" :class="{ 'is-invalid': form.errors.nama_kegiatan }">
            <div class="invalid-feedback">{{ form.errors.nama_kegiatan }}</div>
          </div>
          <button type="submit" class="btn btn-primary mr-2" :disabled="form.processing">Simpan</button>
          <Link :href="route('opsi-kegiatan.index')" class="btn btn-secondary">Batal</Link>
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
  kode_kegiatan: props.item?.kode_kegiatan ?? '',
  nama_kegiatan: props.item?.nama_kegiatan ?? '',
})

function submit() {
  props.item
    ? form.put(route('opsi-kegiatan.update', props.item.id))
    : form.post(route('opsi-kegiatan.store'))
}
</script>
