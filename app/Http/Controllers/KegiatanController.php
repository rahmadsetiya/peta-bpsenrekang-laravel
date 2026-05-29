<?php

namespace App\Http\Controllers;

use App\Models\BlokSensus;
use App\Models\Desa;
use App\Models\Kegiatan;
use App\Models\KegiatanBlokSensus;
use App\Models\KegiatanDesa;
use App\Models\KegiatanSls;
use App\Models\OpsiKegiatan;
use App\Models\Sls;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class KegiatanController extends Controller
{
    private const STATUSES = [
        'disiapkan (IPDS)',
        'digunakan',
        'scan peta',
        'upload peta',
        'selesai',
    ];

    public function index()
    {
        $kegiatan = Kegiatan::with('opsiKegiatan', 'user')
            ->orderByDesc('tahun')
            ->orderBy('bulan')
            ->get();

        return Inertia::render('Kegiatan/Index', [
            'items' => $kegiatan,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create()
    {
        return Inertia::render('Kegiatan/Form', [
            'opsiKegiatan' => OpsiKegiatan::orderBy('kode_kegiatan')->get(),
            'blokSensus' => BlokSensus::orderBy('kode_bs')->get(['id', 'kode_bs', 'nama_bs']),
            'slsList' => Sls::orderBy('kode_sls')->get(['id', 'kode_sls', 'nama_sls']),
            'desaList' => Desa::orderBy('kode_desa')->get(['id', 'kode_desa', 'nama_desa']),
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_opsi_kegiatan' => 'required|exists:opsi_kegiatan,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'tanggal_batas_cetak' => 'nullable|date',
        ]);

        $opsi = OpsiKegiatan::findOrFail($request->id_opsi_kegiatan);
        $id = Str::uuid()->toString();

        $kegiatan = Kegiatan::create([
            'id' => $id,
            'id_opsi_kegiatan' => $request->id_opsi_kegiatan,
            'kode_opsi_kegiatan' => $opsi->kode_kegiatan,
            'id_user' => auth()->id(),
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'tanggal_batas_cetak' => $request->tanggal_batas_cetak,
            'status' => 'disiapkan (IPDS)',
        ]);

        $this->syncWilkerstat($kegiatan, $request);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $user = auth()->user();
        $role = strtolower($user->role);

        if ($role === 'subject_matter' && $kegiatan->id_user !== $user->id) {
            abort(403);
        }

        return Inertia::render('Kegiatan/Form', [
            'kegiatan' => $kegiatan->load('opsiKegiatan', 'blokSensus', 'sls', 'desa'),
            'opsiKegiatan' => OpsiKegiatan::orderBy('kode_kegiatan')->get(),
            'blokSensus' => BlokSensus::orderBy('kode_bs')->get(['id', 'kode_bs', 'nama_bs']),
            'slsList' => Sls::orderBy('kode_sls')->get(['id', 'kode_sls', 'nama_sls']),
            'desaList' => Desa::orderBy('kode_desa')->get(['id', 'kode_desa', 'nama_desa']),
            'statuses' => self::STATUSES,
            'canChangeStatus' => in_array($role, ['admin', 'ipds']),
        ]);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $user = auth()->user();
        $role = strtolower($user->role);

        if ($role === 'subject_matter' && $kegiatan->id_user !== $user->id) {
            abort(403);
        }

        $request->validate([
            'id_opsi_kegiatan' => 'required|exists:opsi_kegiatan,id',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'tanggal_batas_cetak' => 'nullable|date',
        ]);

        $opsi = OpsiKegiatan::findOrFail($request->id_opsi_kegiatan);
        $data = [
            'id_opsi_kegiatan' => $request->id_opsi_kegiatan,
            'kode_opsi_kegiatan' => $opsi->kode_kegiatan,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'tanggal_batas_cetak' => $request->tanggal_batas_cetak,
        ];

        if (in_array($role, ['admin', 'ipds']) && $request->filled('status')) {
            $data['status'] = $request->status;
        }

        $kegiatan->update($data);
        $this->syncWilkerstat($kegiatan, $request);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diupdate.');
    }

    public function detail(Kegiatan $kegiatan)
    {
        return Inertia::render('Kegiatan/Detail', [
            'kegiatan' => $kegiatan->load('opsiKegiatan', 'user', 'blokSensus', 'sls', 'desa', 'wilkerstatPeta.insets'),
            'statuses' => self::STATUSES,
        ]);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    private function syncWilkerstat(Kegiatan $kegiatan, Request $request): void
    {
        if ($request->has('blok_sensus_ids')) {
            $kegiatan->blokSensus()->sync($request->blok_sensus_ids ?? []);
        }
        if ($request->has('sls_ids')) {
            $kegiatan->sls()->sync($request->sls_ids ?? []);
        }
        if ($request->has('desa_ids')) {
            $kegiatan->desa()->sync($request->desa_ids ?? []);
        }
    }
}
