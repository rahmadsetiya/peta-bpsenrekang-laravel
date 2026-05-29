<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-500 rounded-2xl mb-4 shadow-lg">
          <i class="fas fa-map text-white text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-white">
          <span class="text-indigo-400">Peta</span> BPS Enrekang
        </h1>
        <p class="text-slate-400 text-sm mt-1">Sistem Peta Wilkerstat</p>
      </div>

      <!-- Card -->
      <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Masuk ke Akun</h2>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Username -->
          <div>
            <label class="form-label">Username</label>
            <div class="relative">
              <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
              <input
                v-model="form.username"
                type="text"
                class="form-field pl-9"
                :class="{ 'form-field-error': form.errors.username }"
                placeholder="Masukkan username"
                autofocus
                autocomplete="username"
              />
            </div>
            <p v-if="form.errors.username" class="form-error">{{ form.errors.username }}</p>
          </div>

          <!-- Password -->
          <div>
            <label class="form-label">Password</label>
            <div class="relative">
              <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
              <input
                v-model="form.password"
                :type="showPass ? 'text' : 'password'"
                class="form-field pl-9 pr-10"
                :class="{ 'form-field-error': form.errors.password }"
                placeholder="Masukkan password"
                autocomplete="current-password"
              />
              <button
                type="button"
                @click="showPass = !showPass"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
              >
                <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-xs"></i>
              </button>
            </div>
            <p v-if="form.errors.password" class="form-error">{{ form.errors.password }}</p>
          </div>

          <!-- Remember -->
          <div class="flex items-center gap-2 pt-1">
            <input
              v-model="form.remember"
              id="remember"
              type="checkbox"
              class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
            />
            <label for="remember" class="text-sm text-gray-600 cursor-pointer">Ingat saya</label>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="btn btn-primary w-full justify-center py-2.5 text-sm mt-2"
          >
            <i v-if="form.processing" class="fas fa-spinner fa-spin text-xs"></i>
            <span v-else>Masuk</span>
          </button>
        </form>
      </div>

      <p class="text-center text-slate-500 text-xs mt-6">
        BPS Kabupaten Enrekang &copy; {{ year }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const showPass = ref(false)
const year = new Date().getFullYear()

const form = useForm({
  username: '',
  password: '',
  remember: false,
})

function submit() {
  form.post(route('login'))
}
</script>
