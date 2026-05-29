<template>
  <div v-if="links.length > 3" class="flex items-center justify-between px-4 py-3 border-t border-gray-100">
    <p class="text-sm text-gray-500">
      Menampilkan <span class="font-medium text-gray-700">{{ from }}</span>–<span class="font-medium text-gray-700">{{ to }}</span>
      dari <span class="font-medium text-gray-700">{{ total }}</span> data
    </p>
    <div class="flex items-center gap-1">
      <template v-for="link in links" :key="link.label">
        <Link
          v-if="link.url"
          :href="link.url"
          preserve-scroll
          :class="[
            'inline-flex items-center justify-center min-w-[2rem] h-8 px-2 text-sm rounded-lg transition-colors',
            link.active
              ? 'bg-indigo-600 text-white font-semibold'
              : 'text-gray-600 hover:bg-gray-100',
          ]"
          v-html="link.label"
        />
        <span
          v-else
          :class="[
            'inline-flex items-center justify-center min-w-[2rem] h-8 px-2 text-sm rounded-lg',
            link.active ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-300 cursor-default',
          ]"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: { type: Array, default: () => [] },
  from:  { type: Number, default: 0 },
  to:    { type: Number, default: 0 },
  total: { type: Number, default: 0 },
})
</script>
