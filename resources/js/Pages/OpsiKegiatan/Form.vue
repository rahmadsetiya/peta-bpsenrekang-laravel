<template>
  <AppLayout :title="item ? 'Edit Opsi Kegiatan' : 'Tambah Opsi Kegiatan'">
    <div class="max-w-lg">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">{{ item ? 'Edit' : 'Tambah' }} Opsi Kegiatan</h2>
        </div>
        <div class="card-body">
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="form-label">Kode Kegiatan</label>
              <input v-model="form.kode_kegiatan" type="text" class="form-field"
                :class="{ 'form-field-error': form.errors.kode_kegiatan }" />
              <p v-if="form.errors.kode_kegiatan" class="form-error">{{ form.errors.kode_kegiatan }}</p>
            </div>
            <div>
              <label class="form-label">Nama Kegiatan</label>
              <input v-model="form.nama_kegiatan" type="text" class="form-field"
                :class="{ 'form-field-error': form.errors.nama_kegiatan }" />
              <p v-if="form.errors.nama_kegiatan" class="form-error">{{ form.errors.nama_kegiatan }}</p>
            </div>
            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
              <button type="submit" :disabled="form.processing" class="btn btn-primary">
                <i v-if="form.processing" class="fas fa-spinner fa-spin text-xs"></i>
                <span v-else>Simpan</span>
              </button>
              <Link :href="route('opsi-kegiatan.index')" class="btn btn-secondary">Batal</Link>
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
  kode_kegiatan: props.item?.kode_kegiatan ?? '',
  nama_kegiatan: props.item?.nama_kegiatan ?? '',
})

function submit() {
  props.item
    ? form.put(route('opsi-kegiatan.update', props.item.id))
    : form.post(route('opsi-kegiatan.store'))
}
</script>
