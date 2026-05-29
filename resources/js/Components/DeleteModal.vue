<template>
  <Teleport to="body">
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="$emit('cancel')" />
      <Transition
        appear
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 scale-95"
        leave-active-class="transition duration-150 ease-in"
        leave-to-class="opacity-0 scale-95"
      >
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm">
          <div class="p-6">
            <div class="flex items-start gap-4">
              <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
              </div>
              <div>
                <h3 class="text-base font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">
                  Hapus <strong class="text-gray-700">{{ name }}</strong>?
                  Tindakan ini tidak dapat dibatalkan.
                </p>
              </div>
            </div>
          </div>
          <div class="flex justify-end gap-3 px-6 pb-6">
            <button @click="$emit('cancel')" class="btn btn-secondary">Batal</button>
            <button @click="$emit('confirm')" class="btn btn-danger">
              <i class="fas fa-trash" style="font-size:11px"></i>
              Hapus
            </button>
          </div>
        </div>
      </Transition>
    </div>
  </Teleport>
</template>

<script setup>
defineProps({ name: String })
defineEmits(['confirm', 'cancel'])
</script>
