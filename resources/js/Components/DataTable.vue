<template>
  <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Search + actions -->
    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
      <div class="relative">
        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="font-size:11px"></i>
        <input
          :value="modelValue !== undefined ? modelValue : search"
          @input="handleSearch"
          type="text"
          placeholder="Cari..."
          class="pl-8 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                 w-52 placeholder-gray-400 transition"
        />
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        <slot name="actions" />
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead>
          <tr class="bg-gray-50">
            <th class="table-th w-12">#</th>
            <slot name="headers" />
            <th class="table-th text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
          <tr v-if="!filtered.length">
            <td :colspan="colCount" class="px-4 py-12 text-center">
              <div class="flex flex-col items-center gap-2 text-gray-400">
                <i class="fas fa-inbox text-3xl"></i>
                <span class="text-sm">Tidak ada data</span>
              </div>
            </td>
          </tr>
          <tr
            v-for="(row, i) in filtered"
            :key="row.id"
            class="hover:bg-gray-50/60 transition-colors"
          >
            <td class="table-td-muted font-mono text-xs">{{ i + 1 }}</td>
            <slot name="row" :row="row" />
            <td class="px-4 py-3 text-right">
              <div class="flex items-center justify-end gap-1">
                <slot name="rowActions" :row="row" />
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
  items: Array,
  filterFn: Function,
  colCount: { type: Number, default: 3 },
  modelValue: { type: String, default: undefined },
})

const emit = defineEmits(['update:modelValue'])

const search = ref('')

function handleSearch(e) {
  if (props.modelValue !== undefined) {
    emit('update:modelValue', e.target.value)
  } else {
    search.value = e.target.value
  }
}

const filtered = computed(() => {
  // When controlled (server-side), skip client filtering — parent handles it
  if (props.modelValue !== undefined) return props.items
  if (!search.value) return props.items
  if (props.filterFn) return props.items.filter(item => props.filterFn(item, search.value))
  const q = search.value.toLowerCase()
  return props.items.filter(item =>
    Object.values(item).some(v => String(v).toLowerCase().includes(q))
  )
})
</script>
