<?php

namespace App\Http\Controllers;

use App\Models\KegiatanWilkerstatPeta;
use App\Models\Sls;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SlsExport;
use App\Imports\SlsImport;

class SlsController extends Controller
{
    public function index()
    {
        return Inertia::render('Sls/Index', [
            'items' => Sls::orderBy('kode_sls')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Sls/Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_sls' => 'required|unique:sls,kode_sls',
            'nama_sls' => 'required',
        ]);

        Sls::create(['id' => Str::uuid()] + $request->only([
            'kode_sls', 'nama_sls', 'luas',
            'kode_prov', 'kode_kabupaten', 'kode_kecamatan', 'kode_desa',
            'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan', 'nama_desa',
        ]));

        return redirect()->route('sls.index')->with('success', 'SLS berhasil dibuat.');
    }

    public function edit(Sls $sl)
    {
        return Inertia::render('Sls/Form', ['item' => $sl]);
    }

    public function update(Request $request, Sls $sl)
    {
        $request->validate([
            'kode_sls' => 'required|unique:sls,kode_sls,' . $sl->id,
            'nama_sls' => 'required',
        ]);

        $sl->update($request->only([
            'kode_sls', 'nama_sls', 'luas',
            'kode_prov', 'kode_kabupaten', 'kode_kecamatan', 'kode_desa',
            'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan', 'nama_desa',
        ]));

        return redirect()->route('sls.index')->with('success', 'SLS berhasil diupdate.');
    }

    public function destroy(Sls $sl)
    {
        $sl->delete();
        return redirect()->route('sls.index')->with('success', 'SLS berhasil dihapus.');
    }

    public function detail(Sls $sl)
    {
        $kegiatan = $sl->kegiatan()->with('opsiKegiatan')->get();
        $peta = KegiatanWilkerstatPeta::where('id_wilkerstat', $sl->id)
            ->where('wilkerstat_type', 'sls')
            ->whereNull('id_parent_peta')
            ->with('insets')
            ->get();

        return Inertia::render('Sls/Detail', [
            'item' => $sl,
            'kegiatan' => $kegiatan,
            'peta' => $peta,
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new SlsExport, 'sls.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        Excel::import(new SlsImport, $request->file('file'));
        return redirect()->route('sls.index')->with('success', 'Import berhasil.');
    }
}
