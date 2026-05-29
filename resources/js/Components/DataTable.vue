<template>
  <div>
    <div class="mb-3 d-flex justify-content-between align-items-center">
      <input
        v-model="search"
        type="text"
        class="form-control w-25"
        placeholder="Cari..."
      />
      <slot name="actions" />
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <slot name="headers" />
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!filtered.length">
            <td :colspan="colCount" class="text-center text-muted">Tidak ada data</td>
          </tr>
          <tr v-for="(row, i) in filtered" :key="row.id">
            <td>{{ i + 1 }}</td>
            <slot name="row" :row="row" />
            <td>
              <slot name="rowActions" :row="row" />
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
})

const search = ref('')

const filtered = computed(() => {
  if (!search.value) return props.items
  if (props.filterFn) return props.items.filter(item => props.filterFn(item, search.value))
  const q = search.value.toLowerCase()
  return props.items.filter(item =>
    Object.values(item).some(v => String(v).toLowerCase().includes(q))
  )
})
</script>
