<template>
  <div class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <b>Peta</b> BPS Enrekang
      </div>
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Masuk ke sistem peta wilkerstat</p>

          <form @submit.prevent="submit">
            <div class="input-group mb-3">
              <input
                v-model="form.username"
                type="text"
                class="form-control"
                :class="{ 'is-invalid': form.errors.username }"
                placeholder="Username"
                autofocus
              />
              <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-user"></span></div>
              </div>
              <div v-if="form.errors.username" class="invalid-feedback d-block">{{ form.errors.username }}</div>
            </div>

            <div class="input-group mb-3">
              <input
                v-model="form.password"
                :type="showPass ? 'text' : 'password'"
                class="form-control"
                :class="{ 'is-invalid': form.errors.password }"
                placeholder="Password"
              />
              <div class="input-group-append">
                <div class="input-group-text" style="cursor:pointer" @click="showPass = !showPass">
                  <span :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'"></span>
                </div>
              </div>
              <div v-if="form.errors.password" class="invalid-feedback d-block">{{ form.errors.password }}</div>
            </div>

            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input v-model="form.remember" type="checkbox" id="remember">
                  <label for="remember">Ingat saya</label>
                </div>
              </div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block" :disabled="form.processing">
                  <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i></span>
                  <span v-else>Masuk</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const showPass = ref(false)

const form = useForm({
  username: '',
  password: '',
  remember: false,
})

function submit() {
  form.post(route('login'))
}
</script>
