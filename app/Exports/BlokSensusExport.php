<?php

namespace App\Exports;

use App\Models\BlokSensus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BlokSensusExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BlokSensus::orderBy('kode_bs')->get([
            'kode_bs', 'nama_bs', 'luas',
            'kode_prov', 'nama_provinsi',
            'kode_kabupaten', 'nama_kabupaten',
            'kode_kecamatan', 'nama_kecamatan',
            'kode_desa', 'nama_desa',
            'kode_sls', 'nama_sls',
        ]);
    }

    public function headings(): array
    {
        return [
            'Kode BS', 'Nama BS', 'Luas',
            'Kode Provinsi', 'Nama Provinsi',
            'Kode Kabupaten', 'Nama Kabupaten',
            'Kode Kecamatan', 'Nama Kecamatan',
            'Kode Desa', 'Nama Desa',
            'Kode SLS', 'Nama SLS',
        ];
    }
}
