<template>
  <AppLayout :title="user ? 'Edit User' : 'Tambah User'">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ user ? 'Edit User' : 'Tambah User' }}</h3>
      </div>
      <div class="card-body">
        <form @submit.prevent="submit">
          <div class="form-group">
            <label>Username</label>
            <input v-model="form.username" type="text" class="form-control" :class="{ 'is-invalid': form.errors.username }">
            <div class="invalid-feedback">{{ form.errors.username }}</div>
          </div>

          <div class="form-group">
            <label>Password {{ user ? '(kosongkan jika tidak diubah)' : '' }}</label>
            <input v-model="form.password" type="password" class="form-control" :class="{ 'is-invalid': form.errors.password }">
            <div class="invalid-feedback">{{ form.errors.password }}</div>
          </div>

          <div class="form-group">
            <label>Role</label>
            <select v-model="form.role" class="form-control" :class="{ 'is-invalid': form.errors.role }">
              <option value="">Pilih Role</option>
              <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
            </select>
            <div class="invalid-feedback">{{ form.errors.role }}</div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary mr-2" :disabled="form.processing">
              {{ user ? 'Update' : 'Simpan' }}
            </button>
            <Link :href="route('users.index')" class="btn btn-secondary">Batal</Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({ user: Object })

const roles = ['ADMIN', 'IPDS', 'SUBJECT_MATTER', 'GUEST']

const form = useForm({
  username: props.user?.username ?? '',
  password: '',
  role: props.user?.role ?? '',
})

function submit() {
  if (props.user) {
    form.put(route('users.update', props.user.id))
  } else {
    form.post(route('users.store'))
  }
}
</script>
