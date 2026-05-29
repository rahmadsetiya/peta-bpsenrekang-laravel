<template>
  <AppLayout :title="`Kelola Peta — ${kegiatan.opsi_kegiatan?.nama_kegiatan}`">
    <!-- Batch result alert -->
    <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 -translate-y-2">
      <div v-if="batchResult" class="mb-5 rounded-xl border border-sky-200 bg-sky-50 p-4">
        <div class="flex items-start justify-between gap-4">
          <div>
            <p class="text-sm font-semibold text-sky-800">Hasil Batch Upload</p>
            <div class="mt-1 flex flex-wrap gap-3 text-xs text-sky-700">
              <span><strong>{{ batchResult.success }}</strong> berhasil</span>
              <span><strong>{{ batchResult.failed }}</strong> gagal</span>
              <span><strong>{{ batchResult.not_found }}</strong> tidak ditemukan</span>
              <span><strong>{{ batchResult.invalid }}</strong> tidak valid</span>
            </div>
            <ul v-if="batchResult.errors?.length" class="mt-2 space-y-0.5">
              <li v-for="(e, i) in batchResult.errors.slice(0, 5)" :key="i" class="text-xs text-sky-600">{{ e }}</li>
              <li v-if="batchResult.errors.length > 5" class="text-xs text-sky-500">+{{ batchResult.errors.length - 5 }} lainnya</li>
            </ul>
          </div>
          <button @click="dismissBatch" class="text-sky-500 hover:text-sky-700 shrink-0 p-1">
            <i class="fas fa-times text-xs"></i>
          </button>
        </div>
      </div>
    </Transition>

    <div class="flex items-center gap-2 mb-5">
      <Link :href="route('kegiatan.detail', kegiatan.id)" class="btn btn-secondary">
        <i class="fas fa-arrow-left" style="font-size:11px"></i> Kembali
      </Link>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
      <!-- Upload forms -->
      <div class="space-y-4">
        <!-- Single upload -->
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Upload Peta</h2>
          </div>
          <div class="card-body">
            <form @submit.prevent="doUpload" class="space-y-3">
              <div>
                <label class="form-label">Tipe Wilkerstat</label>
                <select v-model="uploadForm.wilkerstat_type" @change="uploadForm.id_wilkerstat = ''" class="form-field">
                  <option value="">-- Pilih --</option>
                  <option value="blok_sensus">Blok Sensus</option>
                  <option value="sls">SLS</option>
                  <option value="desa">Desa</option>
                </select>
              </div>
              <div v-if="uploadForm.wilkerstat_type">
                <label class="form-label">Wilkerstat</label>
                <select v-model="uploadForm.id_wilkerstat" class="form-field">
                  <option value="">-- Pilih --</option>
                  <option v-for="w in wilkerstatList" :key="w.id" :value="w.id">
                    {{ w.kode }} - {{ w.nama }}
                  </option>
                </select>
              </div>
              <div>
                <label class="form-label">Jenis Peta</label>
                <select v-model="uploadForm.jenis_peta" class="form-field">
                  <option value="dengan_titik">Dengan Titik</option>
                  <option value="tanpa_titik">Tanpa Titik</option>
                </select>
              </div>
              <div>
                <label class="form-label">File Peta (JPG/PNG)</label>
                <input type="file" @change="uploadForm.files = $event.target.files" accept="image/*" multiple
                  class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
              </div>
              <div>
                <label class="form-label">Inset dari (opsional)</label>
                <select v-model="uploadForm.id_parent_peta" class="form-field">
                  <option value="">Bukan Inset</option>
                  <option v-for="p in mainPeta" :key="p.id" :value="p.id">{{ p.nama_file }}</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary w-full justify-center">
                <i class="fas fa-upload" style="font-size:11px"></i> Upload
              </button>
            </form>
          </div>
        </div>

        <!-- Batch upload -->
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Batch Upload ZIP</h2>
          </div>
          <div class="card-body">
            <form @submit.prevent="doBatchUpload" class="space-y-3">
              <div>
                <label class="form-label">Jenis Peta</label>
                <select v-model="batchForm.jenis_peta" class="form-field">
                  <option value="dengan_titik">Dengan Titik</option>
                  <option value="tanpa_titik">Tanpa Titik</option>
                </select>
              </div>
              <div>
                <label class="form-label">File ZIP</label>
                <input type="file" @change="batchForm.zip_file = $event.target.files[0]" accept=".zip"
                  class="w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer" />
              </div>
              <button type="submit" class="btn btn-warning w-full justify-center">
                <i class="fas fa-archive" style="font-size:11px"></i> Batch Upload
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Gallery -->
      <div class="xl:col-span-2 card">
        <div class="card-header">
          <h2 class="card-title">Peta Terupload</h2>
          <a :href="route('kelola-peta.download-all', kegiatan.id)" class="btn btn-success" style="padding:4px 12px;font-size:12px">
            <i class="fas fa-download" style="font-size:10px"></i> Download Semua
          </a>
        </div>
        <div class="card-body space-y-4">
          <div v-if="!peta.length" class="flex flex-col items-center gap-2 py-8 text-gray-400">
            <i class="fas fa-images text-4xl"></i>
            <p class="text-sm">Belum ada peta diupload</p>
          </div>

          <div v-for="p in peta" :key="p.id" class="rounded-xl border border-gray-200 overflow-hidden">
            <!-- Peta header -->
            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200">
              <div class="flex items-center gap-2 min-w-0">
                <span :class="['badge', wilkerstatBadge(p.wilkerstat_type)]">{{ p.wilkerstat_type }}</span>
                <span class="badge badge-gray">{{ p.jenis_peta }}</span>
                <span class="text-sm font-medium text-gray-800 truncate">{{ p.nama_file }}</span>
              </div>
              <div class="flex items-center gap-1 shrink-0 ml-2">
                <a :href="route('kelola-peta.download', p.id)" class="btn-icon btn-icon-info" title="Download">
                  <i class="fas fa-download text-xs"></i>
                </a>
                <button @click="openRename(p)" class="btn-icon btn-icon-edit" title="Ganti Nama">
                  <i class="fas fa-pencil-alt text-xs"></i>
                </button>
                <button @click="openReplace(p)" class="btn-icon" style="color:#6b7280" title="Ganti File">
                  <i class="fas fa-exchange-alt text-xs"></i>
                </button>
                <button @click="confirmDelete(p)" class="btn-icon btn-icon-danger" title="Hapus">
                  <i class="fas fa-trash text-xs"></i>
                </button>
              </div>
            </div>

            <!-- Peta body -->
            <div class="p-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <a :href="route('preview-peta', p.file_path)" target="_blank">
                  <img :src="route('preview-peta', p.file_path)" class="w-full rounded-lg border border-gray-200 hover:opacity-90 transition-opacity" />
                </a>
                <p class="text-xs text-gray-400 mt-1">{{ p.uploader }} · {{ formatDate(p.uploaded_at) }}</p>
              </div>
              <div class="sm:col-span-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Inset ({{ p.insets?.length ?? 0 }})</p>
                <div v-if="!p.insets?.length" class="text-xs text-gray-400">Tidak ada inset</div>
                <div class="grid grid-cols-3 gap-2">
                  <div v-for="inset in p.insets" :key="inset.id" class="relative group">
                    <img :src="route('preview-peta', inset.file_path)" class="w-full aspect-square object-cover rounded-lg border border-gray-200" />
                    <button @click="confirmDelete(inset)"
                      class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                      <i class="fas fa-times" style="font-size:8px"></i>
                    </button>
                    <p class="text-xs text-gray-500 mt-1 truncate" :title="inset.nama_file">{{ inset.nama_file }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Rename modal -->
    <Teleport to="body">
      <div v-if="renameTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="renameTarget = null" />
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900">Ganti Nama File</h3>
            <button @click="renameTarget = null" class="text-gray-400 hover:text-gray-600 p-1"><i class="fas fa-times text-sm"></i></button>
          </div>
          <div class="p-6">
            <label class="form-label">Nama Baru</label>
            <input v-model="newName" type="text" class="form-field" />
          </div>
          <div class="flex justify-end gap-3 px-6 pb-6">
            <button @click="renameTarget = null" class="btn btn-secondary">Batal</button>
            <button @click="doRename" class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Replace modal -->
    <Teleport to="body">
      <div v-if="replaceTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="replaceTarget = null" />
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-900">Ganti File Peta</h3>
            <button @click="replaceTarget = null" class="text-gray-400 hover:text-gray-600 p-1"><i class="fas fa-times text-sm"></i></button>
          </div>
          <div class="p-6">
            <label class="form-label">File Baru (JPG/PNG)</label>
            <input type="file" @change="replaceFile = $event.target.files[0]" accept="image/*"
              class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
          </div>
          <div class="flex justify-end gap-3 px-6 pb-6">
            <button @click="replaceTarget = null" class="btn btn-secondary">Batal</button>
            <button @click="doReplace" class="btn btn-primary">Ganti</button>
          </div>
        </div>
      </div>
    </Teleport>

    <DeleteModal v-if="deleteTarget" :name="deleteTarget.nama_file"
      @confirm="doDelete" @cancel="deleteTarget = null" />
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

const uploadForm = ref({ wilkerstat_type: '', id_wilkerstat: '', jenis_peta: 'dengan_titik', files: null, id_parent_peta: '' })
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

const WILKERSTAT_BADGE = {
  blok_sensus: 'badge-indigo',
  sls: 'badge-sky',
  desa: 'badge-emerald',
}
function wilkerstatBadge(t) { return WILKERSTAT_BADGE[t] ?? 'badge-gray' }

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
    onFinish: () => { deleteTarget.value = null },
  })
}

function openRename(p) { renameTarget.value = p; newName.value = p.nama_file }
function doRename() {
  router.post(route('kelola-peta.rename', renameTarget.value.id), { new_nama_file: newName.value }, {
    onFinish: () => { renameTarget.value = null },
  })
}

function openReplace(p) { replaceTarget.value = p }
function doReplace() {
  const data = new FormData()
  if (replaceFile.value) data.append('replace_file', replaceFile.value)
  router.post(route('kelola-peta.replace', replaceTarget.value.id), data, {
    onFinish: () => { replaceTarget.value = null },
  })
}

function dismissBatch() {
  router.reload({ only: [] })
}

function formatDate(d) {
  if (!d) return '-'
  return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>
