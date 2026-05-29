<?php

namespace App\Http\Controllers;

use App\Models\OpsiKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OpsiKegiatanController extends Controller
{
    public function index()
    {
        return Inertia::render('OpsiKegiatan/Index', [
            'items' => OpsiKegiatan::orderBy('kode_kegiatan')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('OpsiKegiatan/Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kegiatan' => 'required|unique:opsi_kegiatan,kode_kegiatan',
            'nama_kegiatan' => 'required',
        ]);

        OpsiKegiatan::create([
            'id' => Str::uuid(),
            'kode_kegiatan' => $request->kode_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
        ]);

        return redirect()->route('opsi-kegiatan.index')->with('success', 'Opsi kegiatan berhasil dibuat.');
    }

    public function edit(OpsiKegiatan $opsiKegiatan)
    {
        return Inertia::render('OpsiKegiatan/Form', ['item' => $opsiKegiatan]);
    }

    public function update(Request $request, OpsiKegiatan $opsiKegiatan)
    {
        $request->validate([
            'kode_kegiatan' => 'required|unique:opsi_kegiatan,kode_kegiatan,' . $opsiKegiatan->id,
            'nama_kegiatan' => 'required',
        ]);

        $opsiKegiatan->update($request->only('kode_kegiatan', 'nama_kegiatan'));

        return redirect()->route('opsi-kegiatan.index')->with('success', 'Opsi kegiatan berhasil diupdate.');
    }

    public function destroy(OpsiKegiatan $opsiKegiatan)
    {
        $opsiKegiatan->delete();
        return redirect()->route('opsi-kegiatan.index')->with('success', 'Opsi kegiatan berhasil dihapus.');
    }
}
