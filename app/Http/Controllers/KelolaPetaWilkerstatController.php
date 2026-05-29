<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanWilkerstatPeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use ZipArchive;

class KelolaPetaWilkerstatController extends Controller
{
    public function index(Kegiatan $kegiatan)
    {
        $kegiatan->load('opsiKegiatan', 'blokSensus', 'sls', 'desa');

        $peta = KegiatanWilkerstatPeta::where('id_kegiatan', $kegiatan->id)
            ->whereNull('id_parent_peta')
            ->with('insets')
            ->get();

        return Inertia::render('KelolaPeta/Index', [
            'kegiatan' => $kegiatan,
            'peta' => $peta,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'wilkerstat_type' => 'required|in:blok_sensus,sls,desa',
            'id_wilkerstat' => 'required',
            'jenis_peta' => 'required|in:dengan_titik,tanpa_titik',
            'peta_files' => 'required|array',
            'peta_files.*' => 'image|mimes:jpg,jpeg,png',
        ]);

        $kegiatan = Kegiatan::findOrFail($request->id_kegiatan);
        $parentId = $request->id_parent_peta;

        foreach ($request->file('peta_files') as $file) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename, 'local_uploads');

            KegiatanWilkerstatPeta::create([
                'id' => Str::uuid(),
                'id_kegiatan' => $kegiatan->id,
                'wilkerstat_type' => $request->wilkerstat_type,
                'id_wilkerstat' => $request->id_wilkerstat,
                'jenis_peta' => $request->jenis_peta,
                'id_parent_peta' => $parentId,
                'file_path' => $filename,
                'nama_file' => $file->getClientOriginalName(),
                'uploaded_at' => now(),
                'uploader' => auth()->user()->username,
            ]);
        }

        return redirect()->route('kelola-peta.index', $kegiatan->id)->with('success', 'Peta berhasil diupload.');
    }

    public function batchUpload(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'jenis_peta' => 'required|in:dengan_titik,tanpa_titik',
            'zip_file' => 'required|mimes:zip',
        ]);

        $kegiatan = Kegiatan::findOrFail($request->id_kegiatan)->load('blokSensus', 'sls', 'desa');
        $zip = new ZipArchive;
        $tmpPath = $request->file('zip_file')->getRealPath();

        if ($zip->open($tmpPath) !== true) {
            return back()->withErrors(['zip_file' => 'Gagal membuka file ZIP.']);
        }

        $validCodes = $this->buildValidCodes($kegiatan);
        $results = ['success' => 0, 'failed' => 0, 'not_found' => 0, 'invalid' => 0, 'errors' => []];
        $uploadDir = storage_path('app/uploads');
        if (! is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if (! in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $results['invalid']++;
                $results['errors'][] = "$name: bukan file gambar.";
                continue;
            }

            $basename = pathinfo($name, PATHINFO_FILENAME);
            $parts = explode('_', $basename);
            $kode = count($parts) >= 2 ? implode('_', array_slice($parts, 0, -1)) : $basename;

            if (! isset($validCodes[$kode])) {
                $results['not_found']++;
                $results['errors'][] = "$name: kode '$kode' tidak ditemukan.";
                continue;
            }

            [$type, $id] = $validCodes[$kode];
            $filename = uniqid() . '.' . $ext;
            file_put_contents("$uploadDir/$filename", $zip->getFromIndex($i));

            KegiatanWilkerstatPeta::create([
                'id' => Str::uuid(),
                'id_kegiatan' => $kegiatan->id,
                'wilkerstat_type' => $type,
                'id_wilkerstat' => $id,
                'jenis_peta' => $request->jenis_peta,
                'file_path' => $filename,
                'nama_file' => $basename,
                'uploaded_at' => now(),
                'uploader' => auth()->user()->username,
            ]);

            $results['success']++;
        }

        $zip->close();

        return redirect()->route('kelola-peta.index', $kegiatan->id)
            ->with('batchResult', $results);
    }

    public function download(KegiatanWilkerstatPeta $peta)
    {
        $path = storage_path('app/uploads/' . $peta->file_path);
        if (! file_exists($path)) abort(404);
        return response()->download($path, $peta->nama_file);
    }

    public function downloadAll(Kegiatan $kegiatan)
    {
        $peta = KegiatanWilkerstatPeta::where('id_kegiatan', $kegiatan->id)->get();
        $zipPath = storage_path('app/temp_' . Str::random(8) . '.zip');
        $zip = new ZipArchive;
        $zip->open($zipPath, ZipArchive::CREATE);

        foreach ($peta as $p) {
            $filePath = storage_path('app/uploads/' . $p->file_path);
            if (file_exists($filePath)) {
                $folder = $p->wilkerstat_type . '/' . ($p->id_parent_peta ? 'inset/' : '');
                $zip->addFile($filePath, $folder . $p->nama_file);
            }
        }

        $zip->close();
        return response()->download($zipPath, 'peta_' . $kegiatan->id . '.zip')->deleteFileAfterSend();
    }

    public function destroy(KegiatanWilkerstatPeta $peta)
    {
        $insets = $peta->insets;
        foreach ($insets as $inset) {
            @unlink(storage_path('app/uploads/' . $inset->file_path));
            $inset->delete();
        }
        @unlink(storage_path('app/uploads/' . $peta->file_path));
        $kegiatanId = $peta->id_kegiatan;
        $peta->delete();

        return redirect()->route('kelola-peta.index', $kegiatanId)->with('success', 'Peta berhasil dihapus.');
    }

    public function replace(Request $request, KegiatanWilkerstatPeta $peta)
    {
        $request->validate(['replace_file' => 'required|image|mimes:jpg,jpeg,png']);

        @unlink(storage_path('app/uploads/' . $peta->file_path));
        $file = $request->file('replace_file');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(storage_path('app/uploads'), $filename);

        $peta->update(['file_path' => $filename]);

        return redirect()->route('kelola-peta.index', $peta->id_kegiatan)->with('success', 'Peta berhasil diganti.');
    }

    public function rename(Request $request, KegiatanWilkerstatPeta $peta)
    {
        $request->validate(['new_nama_file' => 'required|string']);
        $peta->update(['nama_file' => $request->new_nama_file]);
        return redirect()->route('kelola-peta.index', $peta->id_kegiatan)->with('success', 'Nama peta berhasil diubah.');
    }

    public function preview(string $filename)
    {
        $path = storage_path('app/uploads/' . $filename);
        if (! file_exists($path)) abort(404);
        $mime = mime_content_type($path);
        return response()->file($path, ['Content-Type' => $mime]);
    }

    private function buildValidCodes(Kegiatan $kegiatan): array
    {
        $codes = [];
        foreach ($kegiatan->blokSensus as $bs) {
            $codes[$bs->kode_bs] = ['blok_sensus', $bs->id];
        }
        foreach ($kegiatan->sls as $sls) {
            $codes[$sls->kode_sls] = ['sls', $sls->id];
        }
        foreach ($kegiatan->desa as $desa) {
            $codes[$desa->kode_desa] = ['desa', $desa->id];
        }
        return $codes;
    }
}
