<template>
  <AppLayout :title="user ? 'Edit User' : 'Tambah User'">
    <div class="max-w-md">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">{{ user ? 'Edit User' : 'Tambah User' }}</h2>
        </div>
        <div class="card-body">
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="form-label">Username</label>
              <input v-model="form.username" type="text" class="form-field"
                :class="{ 'form-field-error': form.errors.username }"
                autocomplete="username" />
              <p v-if="form.errors.username" class="form-error">{{ form.errors.username }}</p>
            </div>

            <div>
              <label class="form-label">
                Password
                <span v-if="user" class="text-gray-400 font-normal ml-1">(kosongkan jika tidak diubah)</span>
              </label>
              <input v-model="form.password" type="password" class="form-field"
                :class="{ 'form-field-error': form.errors.password }"
                autocomplete="new-password" />
              <p v-if="form.errors.password" class="form-error">{{ form.errors.password }}</p>
            </div>

            <div>
              <label class="form-label">Role</label>
              <select v-model="form.role" class="form-field"
                :class="{ 'form-field-error': form.errors.role }">
                <option value="">Pilih Role</option>
                <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
              </select>
              <p v-if="form.errors.role" class="form-error">{{ form.errors.role }}</p>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
              <button type="submit" :disabled="form.processing" class="btn btn-primary">
                <i v-if="form.processing" class="fas fa-spinner fa-spin text-xs"></i>
                <span v-else>{{ user ? 'Update' : 'Simpan' }}</span>
              </button>
              <Link :href="route('users.index')" class="btn btn-secondary">Batal</Link>
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

const props = defineProps({ user: Object })

const roles = ['ADMIN', 'IPDS', 'SUBJECT_MATTER', 'GUEST']

const form = useForm({
  username: props.user?.username ?? '',
  password: '',
  role: props.user?.role ?? '',
})

function submit() {
  props.user
    ? form.put(route('users.update', props.user.id))
    : form.post(route('users.store'))
}
</script>
