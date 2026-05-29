<?php

namespace App\Http\Controllers;

use App\Models\BlokSensus;
use App\Models\Kegiatan;
use App\Models\KegiatanWilkerstatPeta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BlokSensusExport;
use App\Imports\BlokSensusImport;

class BlokSensusController extends Controller
{
    public function index()
    {
        return Inertia::render('BlokSensus/Index', [
            'items' => BlokSensus::orderBy('kode_bs')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('BlokSensus/Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_bs' => 'required|unique:blok_sensus,kode_bs',
            'nama_bs' => 'required',
        ]);

        BlokSensus::create(['id' => Str::uuid()] + $request->only([
            'kode_bs', 'nama_bs', 'luas',
            'kode_prov', 'kode_kabupaten', 'kode_kecamatan', 'kode_desa', 'kode_sls',
            'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan', 'nama_desa', 'nama_sls',
        ]));

        return redirect()->route('blok-sensus.index')->with('success', 'Blok sensus berhasil dibuat.');
    }

    public function edit(BlokSensus $blokSensu)
    {
        return Inertia::render('BlokSensus/Form', ['item' => $blokSensu]);
    }

    public function update(Request $request, BlokSensus $blokSensu)
    {
        $request->validate([
            'kode_bs' => 'required|unique:blok_sensus,kode_bs,' . $blokSensu->id,
            'nama_bs' => 'required',
        ]);

        $blokSensu->update($request->only([
            'kode_bs', 'nama_bs', 'luas',
            'kode_prov', 'kode_kabupaten', 'kode_kecamatan', 'kode_desa', 'kode_sls',
            'nama_provinsi', 'nama_kabupaten', 'nama_kecamatan', 'nama_desa', 'nama_sls',
        ]));

        return redirect()->route('blok-sensus.index')->with('success', 'Blok sensus berhasil diupdate.');
    }

    public function destroy(BlokSensus $blokSensu)
    {
        $blokSensu->delete();
        return redirect()->route('blok-sensus.index')->with('success', 'Blok sensus berhasil dihapus.');
    }

    public function detail(BlokSensus $blokSensu)
    {
        $kegiatan = $blokSensu->kegiatan()->with('opsiKegiatan')->get();
        $latestPeta = KegiatanWilkerstatPeta::where('id_wilkerstat', $blokSensu->id)
            ->where('wilkerstat_type', 'blok_sensus')
            ->whereNull('id_parent_peta')
            ->with('insets')
            ->get();

        return Inertia::render('BlokSensus/Detail', [
            'item' => $blokSensu,
            'kegiatan' => $kegiatan,
            'peta' => $latestPeta,
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new BlokSensusExport, 'blok_sensus.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        Excel::import(new BlokSensusImport, $request->file('file'));
        return redirect()->route('blok-sensus.index')->with('success', 'Import berhasil.');
    }
}
