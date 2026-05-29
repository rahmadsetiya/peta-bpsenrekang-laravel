<template>
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <Link :href="route('dashboard')" class="nav-link">Home</Link>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <span class="nav-link text-sm">
            <i class="fas fa-user-circle mr-1"></i>
            {{ auth.username }}
            <span class="badge badge-secondary ml-1">{{ auth.role }}</span>
          </span>
        </li>
        <li class="nav-item">
          <form @submit.prevent="logout">
            <button type="submit" class="nav-link btn btn-link text-danger">
              <i class="fas fa-sign-out-alt"></i> Logout
            </button>
          </form>
        </li>
      </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <Link :href="route('dashboard')" class="brand-link">
        <span class="brand-text font-weight-light">
          <strong>Peta</strong> BPS Enrekang
        </span>
      </Link>

      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <li class="nav-item">
              <Link :href="route('dashboard')" class="nav-link" :class="{ active: isActive('dashboard') }">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </Link>
            </li>

            <li class="nav-item">
              <Link :href="route('kegiatan.index')" class="nav-link" :class="{ active: isActive('kegiatan') }">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Kegiatan</p>
              </Link>
            </li>

            <template v-if="isRole('ADMIN', 'IPDS')">
              <li class="nav-item">
                <Link :href="route('opsi-kegiatan.index')" class="nav-link" :class="{ active: isActive('opsi-kegiatan') }">
                  <i class="nav-icon fas fa-list"></i>
                  <p>Opsi Kegiatan</p>
                </Link>
              </li>

              <li class="nav-item has-treeview" :class="{ 'menu-open': isActive('blok-sensus') || isActive('sls') || isActive('desa') }">
                <a href="#" class="nav-link" :class="{ active: isActive('blok-sensus') || isActive('sls') || isActive('desa') }">
                  <i class="nav-icon fas fa-map"></i>
                  <p>Wilkerstat <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <Link :href="route('blok-sensus.index')" class="nav-link" :class="{ active: isActive('blok-sensus') }">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Blok Sensus</p>
                    </Link>
                  </li>
                  <li class="nav-item">
                    <Link :href="route('sls.index')" class="nav-link" :class="{ active: isActive('sls') }">
                      <i class="far fa-circle nav-icon"></i>
                      <p>SLS</p>
                    </Link>
                  </li>
                  <li class="nav-item">
                    <Link :href="route('desa.index')" class="nav-link" :class="{ active: isActive('desa') }">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Desa</p>
                    </Link>
                  </li>
                </ul>
              </li>
            </template>

            <li v-if="isRole('ADMIN')" class="nav-item">
              <Link :href="route('users.index')" class="nav-link" :class="{ active: isActive('users') }">
                <i class="nav-icon fas fa-users"></i>
                <p>Users</p>
              </Link>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">{{ title }}</h1>
            </div>
          </div>
        </div>
      </div>

      <section class="content">
        <div class="container-fluid">
          <!-- Flash messages -->
          <div v-if="flash.success" class="alert alert-success alert-dismissible fade show">
            {{ flash.success }}
            <button type="button" class="close" @click="dismissFlash">
              <span>&times;</span>
            </button>
          </div>
          <div v-if="flash.error" class="alert alert-danger alert-dismissible fade show">
            {{ flash.error }}
            <button type="button" class="close" @click="dismissFlash">
              <span>&times;</span>
            </button>
          </div>

          <slot />
        </div>
      </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
      <strong>BPS Kabupaten Enrekang</strong>
      <div class="float-right d-none d-sm-inline-block">
        <b>Peta Wilkerstat</b>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({ title: String })
const page = usePage()
const auth = computed(() => page.props.auth.user)
const flash = computed(() => page.props.flash ?? {})
const showFlash = ref(true)

function isActive(segment) {
  return window.location.pathname.includes(segment)
}

function isRole(...roles) {
  return roles.includes(auth.value?.role)
}

function logout() {
  router.post(route('logout'))
}

function dismissFlash() {
  showFlash.value = false
}
</script>
