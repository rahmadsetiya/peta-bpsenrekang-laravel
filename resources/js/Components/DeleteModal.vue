<template>
  <div class="modal fade show d-block" style="background:rgba(0,0,0,.5)">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Hapus</h5>
        </div>
        <div class="modal-body">
          Hapus <strong>{{ name }}</strong>?
        </div>
        <div class="modal-footer">
          <button @click="$emit('cancel')" class="btn btn-secondary">Batal</button>
          <button @click="$emit('confirm')" class="btn btn-danger">Hapus</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({ name: String })
defineEmits(['confirm', 'cancel'])
</script>
