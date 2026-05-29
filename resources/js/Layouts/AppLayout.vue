<template>
  <!-- Mobile overlay -->
  <Transition
    enter-active-class="transition-opacity duration-200"
    leave-active-class="transition-opacity duration-200"
    enter-from-class="opacity-0"
    leave-to-class="opacity-0"
  >
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 z-20 bg-black/60 lg:hidden"
      @click="sidebarOpen = false"
    />
  </Transition>

  <div class="flex h-screen overflow-hidden bg-gray-50">
    <!-- ── Sidebar ───────────────────────────────────────────── -->
    <aside
      :class="[
        'fixed lg:static inset-y-0 left-0 z-30 w-64 flex flex-col bg-slate-900',
        'transition-transform duration-300 ease-in-out',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
      ]"
    >
      <!-- Brand -->
      <div class="flex items-center h-16 px-5 border-b border-slate-700/50 shrink-0">
        <Link :href="route('dashboard')" class="flex items-center gap-3 min-w-0">
          <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center shrink-0">
            <i class="fas fa-map text-white" style="font-size:13px"></i>
          </div>
          <div class="leading-tight min-w-0">
            <span class="text-white font-semibold text-sm block">
              <span class="text-indigo-400">Peta</span> BPS Enrekang
            </span>
            <span class="text-slate-400 text-xs">Sistem Wilkerstat</span>
          </div>
        </Link>
      </div>

      <!-- Nav -->
      <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5">
        <Link :href="route('dashboard')" :class="navItemClass('dashboard')">
          <i class="fas fa-home w-4 text-center text-sm"></i>
          <span>Dashboard</span>
        </Link>

        <Link :href="route('kegiatan.index')" :class="navItemClass('kegiatan')">
          <i class="fas fa-tasks w-4 text-center text-sm"></i>
          <span>Kegiatan</span>
        </Link>

        <template v-if="isRole('ADMIN', 'IPDS')">
          <Link :href="route('opsi-kegiatan.index')" :class="navItemClass('opsi-kegiatan')">
            <i class="fas fa-list-alt w-4 text-center text-sm"></i>
            <span>Opsi Kegiatan</span>
          </Link>

          <div class="pt-4 pb-1 px-2">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Wilkerstat</p>
          </div>

          <Link :href="route('blok-sensus.index')" :class="navItemClass('blok-sensus')">
            <i class="fas fa-th-large w-4 text-center text-sm"></i>
            <span>Blok Sensus</span>
          </Link>

          <Link :href="route('sls.index')" :class="navItemClass('sls')">
            <i class="fas fa-layer-group w-4 text-center text-sm"></i>
            <span>SLS</span>
          </Link>

          <Link :href="route('desa.index')" :class="navItemClass('desa')">
            <i class="fas fa-map-marker-alt w-4 text-center text-sm"></i>
            <span>Desa</span>
          </Link>
        </template>

        <template v-if="isRole('ADMIN')">
          <div class="pt-4 pb-1 px-2">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Manajemen</p>
          </div>
          <Link :href="route('users.index')" :class="navItemClass('users')">
            <i class="fas fa-users w-4 text-center text-sm"></i>
            <span>Users</span>
          </Link>
        </template>
      </nav>

      <!-- User profile -->
      <div class="border-t border-slate-700/50 p-4 shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center shrink-0">
            <span class="text-white text-xs font-bold">{{ auth.username?.[0]?.toUpperCase() }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-white text-sm font-medium truncate">{{ auth.username }}</p>
            <p class="text-slate-400 text-xs truncate">{{ auth.role }}</p>
          </div>
          <button
            @click="logout"
            class="text-slate-400 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-slate-800"
            title="Logout"
          >
            <i class="fas fa-sign-out-alt text-sm"></i>
          </button>
        </div>
      </div>
    </aside>

    <!-- ── Main ──────────────────────────────────────────────── -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <!-- Top bar -->
      <header class="flex items-center h-16 px-4 sm:px-6 bg-white border-b border-gray-200 shrink-0 gap-4">
        <button
          @click="sidebarOpen = !sidebarOpen"
          class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors"
        >
          <i class="fas fa-bars"></i>
        </button>
        <h1 class="text-base font-semibold text-gray-900 truncate">{{ title }}</h1>
      </header>

      <!-- Content -->
      <main class="flex-1 overflow-y-auto">
        <div class="p-4 sm:p-6 max-w-screen-xl mx-auto">
          <!-- Flash: success -->
          <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            leave-active-class="transition-all duration-200 ease-in"
            leave-to-class="opacity-0 -translate-y-2"
          >
            <div
              v-if="showFlash && flash.success"
              class="mb-5 flex items-start gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl"
            >
              <i class="fas fa-check-circle text-emerald-500 mt-0.5 shrink-0"></i>
              <span class="text-sm flex-1">{{ flash.success }}</span>
              <button @click="showFlash = false" class="text-emerald-500 hover:text-emerald-700 shrink-0 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
            </div>
          </Transition>

          <!-- Flash: error -->
          <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            leave-active-class="transition-all duration-200 ease-in"
            leave-to-class="opacity-0 -translate-y-2"
          >
            <div
              v-if="showFlash && flash.error"
              class="mb-5 flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl"
            >
              <i class="fas fa-exclamation-circle text-red-500 mt-0.5 shrink-0"></i>
              <span class="text-sm flex-1">{{ flash.error }}</span>
              <button @click="showFlash = false" class="text-red-500 hover:text-red-700 shrink-0 transition-colors">
                <i class="fas fa-times text-xs"></i>
              </button>
            </div>
          </Transition>

          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'

defineProps({ title: String })

const page = usePage()
const auth = computed(() => page.props.auth.user)
const flash = computed(() => page.props.flash ?? {})
const showFlash = ref(true)
const sidebarOpen = ref(false)

watch(flash, () => { showFlash.value = true })

function navItemClass(segment) {
  const active = window.location.pathname.includes(`/${segment}`)
  return [
    'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors cursor-pointer',
    active
      ? 'bg-indigo-600 text-white'
      : 'text-slate-400 hover:text-white hover:bg-slate-800',
  ]
}

function isRole(...roles) {
  return roles.includes(auth.value?.role)
}

function logout() {
  router.post(route('logout'))
}
</script>
