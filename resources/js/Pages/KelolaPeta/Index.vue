<template>
  <AppLayout :title="`Kelola Peta - ${kegiatan.opsi_kegiatan?.nama_kegiatan}`">
    <!-- Batch upload result alert -->
    <div v-if="batchResult" class="alert alert-info alert-dismissible">
      <strong>Hasil Batch Upload:</strong>
      Berhasil: {{ batchResult.success }},
      Gagal: {{ batchResult.failed }},
      Tidak Ditemukan: {{ batchResult.not_found }},
      Tidak Valid: {{ batchResult.invalid }}
      <ul v-if="batchResult.errors?.length" class="mt-1 mb-0">
        <li v-for="(e, i) in batchResult.errors.slice(0, 10)" :key="i" class="small">{{ e }}</li>
        <li v-if="batchResult.errors.length > 10" class="small">+{{ batchResult.errors.length - 10 }} lainnya</li>
      </ul>
      <button type="button" class="close" @click="dismissBatch"><span>&times;</span></button>
    </div>

    <div class="row">
      <!-- Upload Forms -->
      <div class="col-md-4">
        <div class="card card-primary">
          <div class="card-header"><h3 class="card-title">Upload Peta</h3></div>
          <div class="card-body">
            <form @submit.prevent="doUpload" enctype="multipart/form-data">
              <div class="form-group">
                <label>Tipe Wilkerstat</label>
                <select v-model="uploadForm.wilkerstat_type" class="form-control" @change="uploadForm.id_wilkerstat = ''">
                  <option value="">-- Pilih --</option>
                  <option value="blok_sensus">Blok Sensus</option>
                  <option value="sls">SLS</option>
                  <option value="desa">Desa</option>
                </select>
              </div>
              <div class="form-group" v-if="uploadForm.wilkerstat_type">
                <label>Wilkerstat</label>
                <select v-model="uploadForm.id_wilkerstat" class="form-control">
                  <option value="">-- Pilih --</option>
                  <option v-for="w in wilkerstatList" :key="w.id" :value="w.id">
                    {{ w.kode }} - {{ w.nama }}
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label>Jenis Peta</label>
                <select v-model="uploadForm.jenis_peta" class="form-control">
                  <option value="dengan_titik">Dengan Titik</option>
                  <option value="tanpa_titik">Tanpa Titik</option>
                </select>
              </div>
              <div class="form-group">
                <label>File Peta (JPG/PNG)</label>
                <input type="file" @change="uploadForm.files = $event.target.files" accept="image/*" multiple class="form-control-file">
              </div>
              <div class="form-group">
                <label>Inset dari (opsional)</label>
                <select v-model="uploadForm.id_parent_peta" class="form-control">
                  <option value="">Bukan Inset</option>
                  <option v-for="p in mainPeta" :key="p.id" :value="p.id">{{ p.nama_file }}</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Upload</button>
            </form>
          </div>
        </div>

        <div class="card card-warning">
          <div class="card-header"><h3 class="card-title">Batch Upload ZIP</h3></div>
          <div class="card-body">
            <form @submit.prevent="doBatchUpload">
              <div class="form-group">
                <label>Jenis Peta</label>
                <select v-model="batchForm.jenis_peta" class="form-control">
                  <option value="dengan_titik">Dengan Titik</option>
                  <option value="tanpa_titik">Tanpa Titik</option>
                </select>
              </div>
              <div class="form-group">
                <label>File ZIP</label>
                <input type="file" @change="batchForm.zip_file = $event.target.files[0]" accept=".zip" class="form-control-file">
              </div>
              <button type="submit" class="btn btn-warning btn-block">Batch Upload</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Peta Gallery -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Peta Terupload</h3>
            <a :href="route('kelola-peta.download-all', kegiatan.id)" class="btn btn-sm btn-success">
              <i class="fas fa-download"></i> Download Semua
            </a>
          </div>
          <div class="card-body">
            <div v-if="!peta.length" class="text-muted text-center py-3">Belum ada peta</div>
            <div v-for="p in peta" :key="p.id" class="card card-outline mb-3">
              <div class="card-header">
                <h6 class="card-title">
                  <span class="badge badge-primary mr-1">{{ p.wilkerstat_type }}</span>
                  <span class="badge badge-info mr-1">{{ p.jenis_peta }}</span>
                  {{ p.nama_file }}
                </h6>
                <div class="card-tools">
                  <a :href="route('kelola-peta.download', p.id)" class="btn btn-xs btn-success mr-1">
                    <i class="fas fa-download"></i>
                  </a>
                  <button @click="openRename(p)" class="btn btn-xs btn-warning mr-1">
                    <i class="fas fa-pen"></i>
                  </button>
                  <button @click="openReplace(p)" class="btn btn-xs btn-secondary mr-1">
                    <i class="fas fa-exchange-alt"></i>
                  </button>
                  <button @click="confirmDelete(p)" class="btn btn-xs btn-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-2 row">
                <div class="col-md-4">
                  <a :href="route('preview-peta', p.file_path)" target="_blank">
                    <img :src="route('preview-peta', p.file_path)" class="img-fluid rounded">
                  </a>
                  <small class="d-block text-muted">{{ p.uploader }} - {{ formatDate(p.uploaded_at) }}</small>
                </div>
                <div class="col-md-8">
                  <h6>Inset ({{ p.insets?.length ?? 0 }})</h6>
                  <div class="row">
                    <div v-for="inset in p.insets" :key="inset.id" class="col-4 mb-2">
                      <div class="position-relative">
                        <img :src="route('preview-peta', inset.file_path)" class="img-fluid rounded">
                        <button @click="confirmDelete(inset)" class="btn btn-xs btn-danger position-absolute" style="top:2px;right:2px">
                          <i class="fas fa-times"></i>
                        </button>
                        <p class="small mt-1 mb-0 text-truncate">{{ inset.nama_file }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rename Modal -->
    <div v-if="renameTarget" class="modal fade show d-block" style="background:rgba(0,0,0,.5)">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"><h5>Ganti Nama</h5></div>
          <div class="modal-body">
            <input v-model="newName" type="text" class="form-control">
          </div>
          <div class="modal-footer">
            <button @click="renameTarget = null" class="btn btn-secondary">Batal</button>
            <button @click="doRename" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Replace Modal -->
    <div v-if="replaceTarget" class="modal fade show d-block" style="background:rgba(0,0,0,.5)">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"><h5>Ganti File</h5></div>
          <div class="modal-body">
            <input type="file" @change="replaceFile = $event.target.files[0]" accept="image/*" class="form-control-file">
          </div>
          <div class="modal-footer">
            <button @click="replaceTarget = null" class="btn btn-secondary">Batal</button>
            <button @click="doReplace" class="btn btn-primary">Ganti</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <DeleteModal v-if="deleteTarget" :name="deleteTarget.nama_file"
      @confirm="doDelete" @cancel="deleteTarget = null" />

    <div class="mt-3">
      <Link :href="route('kegiatan.detail', kegiatan.id)" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Kegiatan
      </Link>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DeleteModal from '@/Components/DeleteModal.vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({ kegiatan: Object, peta: Array })
const page = usePage()
const batchResult = computed(() => page.props.flash?.batchResult)

const mainPeta = computed(() => props.peta.filter(p => !p.id_parent_peta))

const uploadForm = ref({
  wilkerstat_type: '',
  id_wilkerstat: '',
  jenis_peta: 'dengan_titik',
  files: null,
  id_parent_peta: '',
})
const batchForm = ref({ jenis_peta: 'dengan_titik', zip_file: null })

const deleteTarget = ref(null)
const renameTarget = ref(null)
const newName = ref('')
const replaceTarget = ref(null)
const replaceFile = ref(null)

const wilkerstatList = computed(() => {
  const type = uploadForm.value.wilkerstat_type
  if (type === 'blok_sensus') return (props.kegiatan.blok_sensus ?? []).map(b => ({ id: b.id, kode: b.kode_bs, nama: b.nama_bs }))
  if (type === 'sls') return (props.kegiatan.sls ?? []).map(s => ({ id: s.id, kode: s.kode_sls, nama: s.nama_sls }))
  if (type === 'desa') return (props.kegiatan.desa ?? []).map(d => ({ id: d.id, kode: d.kode_desa, nama: d.nama_desa }))
  return []
})

function doUpload() {
  const data = new FormData()
  data.append('id_kegiatan', props.kegiatan.id)
  data.append('wilkerstat_type', uploadForm.value.wilkerstat_type)
  data.append('id_wilkerstat', uploadForm.value.id_wilkerstat)
  data.append('jenis_peta', uploadForm.value.jenis_peta)
  data.append('id_parent_peta', uploadForm.value.id_parent_peta)
  if (uploadForm.value.files) {
    Array.from(uploadForm.value.files).forEach(f => data.append('peta_files[]', f))
  }
  router.post(route('kelola-peta.upload'), data)
}

function doBatchUpload() {
  const data = new FormData()
  data.append('id_kegiatan', props.kegiatan.id)
  data.append('jenis_peta', batchForm.value.jenis_peta)
  if (batchForm.value.zip_file) data.append('zip_file', batchForm.value.zip_file)
  router.post(route('kelola-peta.batch-upload'), data)
}

function confirmDelete(p) { deleteTarget.value = p }
function doDelete() {
  router.delete(route('kelola-peta.destroy', deleteTarget.value.id), {
    onFinish: () => { deleteTarget.value = null }
  })
}

function openRename(p) { renameTarget.value = p; newName.value = p.nama_file }
function doRename() {
  router.post(route('kelola-peta.rename', renameTarget.value.id), { new_nama_file: newName.value }, {
    onFinish: () => { renameTarget.value = null }
  })
}

function openReplace(p) { replaceTarget.value = p }
function doReplace() {
  const data = new FormData()
  if (replaceFile.value) data.append('replace_file', replaceFile.value)
  router.post(route('kelola-peta.replace', replaceTarget.value.id), data, {
    onFinish: () => { replaceTarget.value = null }
  })
}

function dismissBatch() {
  // just clear session (reload page)
  router.reload({ only: [] })
}

function formatDate(d) {
  if (!d) return '-'
  return new Date(d).toLocaleDateString('id-ID')
}
</script>
