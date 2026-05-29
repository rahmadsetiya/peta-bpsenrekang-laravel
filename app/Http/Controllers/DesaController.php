<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\KegiatanWilkerstatPeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DesaExport;
use App\Imports\DesaImport;

class DesaController extends Controller
{
    public function index()
    {
        return Inertia::render('Desa/Index', [
            'items' => Desa::orderBy('kode_desa')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Desa/Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_desa' => 'required|unique:desa,kode_desa',
            'nama_desa' => 'required',
        ]);

        Desa::create(['id' => Str::uuid()] + $request->only([
            'kode_desa', 'nama_desa', 'luas',
            'kode_prov', 'kode_kabupaten', 'kode_kecamatan',
            'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan',
        ]));

        return redirect()->route('desa.index')->with('success', 'Desa berhasil dibuat.');
    }

    public function edit(Desa $desa)
    {
        return Inertia::render('Desa/Form', ['item' => $desa]);
    }

    public function update(Request $request, Desa $desa)
    {
        $request->validate([
            'kode_desa' => 'required|unique:desa,kode_desa,' . $desa->id,
            'nama_desa' => 'required',
        ]);

        $desa->update($request->only([
            'kode_desa', 'nama_desa', 'luas',
            'kode_prov', 'kode_kabupaten', 'kode_kecamatan',
            'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan',
        ]));

        return redirect()->route('desa.index')->with('success', 'Desa berhasil diupdate.');
    }

    public function destroy(Desa $desa)
    {
        $desa->delete();
        return redirect()->route('desa.index')->with('success', 'Desa berhasil dihapus.');
    }

    public function detail(Desa $desa)
    {
        $kegiatan = $desa->kegiatan()->with('opsiKegiatan')->get();
        $peta = KegiatanWilkerstatPeta::where('id_wilkerstat', $desa->id)
            ->where('wilkerstat_type', 'desa')
            ->whereNull('id_parent_peta')
            ->with('insets')
            ->get();

        return Inertia::render('Desa/Detail', [
            'item' => $desa,
            'kegiatan' => $kegiatan,
            'peta' => $peta,
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new DesaExport, 'desa.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        Excel::import(new DesaImport, $request->file('file'));
        return redirect()->route('desa.index')->with('success', 'Import berhasil.');
    }
}
