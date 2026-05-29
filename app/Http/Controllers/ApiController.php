<?php

namespace App\Http\Controllers;

use App\Models\BlokSensus;
use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\Sls;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function kecamatan(Request $request): JsonResponse
    {
        $rows = Kecamatan::where('kode_prov', $request->kode_prov)
            ->where('kode_kabupaten', $request->kode_kab)
            ->pluck('nama_kecamatan', 'kode_kecamatan');

        return response()->json(['status' => 'success', 'data' => $rows]);
    }

    public function desa(Request $request): JsonResponse
    {
        $rows = Desa::where('kode_prov', $request->kode_prov)
            ->where('kode_kabupaten', $request->kode_kab)
            ->where('kode_kecamatan', $request->kode_kec)
            ->pluck('nama_desa', 'kode_desa');

        return response()->json(['status' => 'success', 'data' => $rows]);
    }

    public function sls(Request $request): JsonResponse
    {
        $rows = Sls::where('kode_desa', $request->kode_desa)
            ->pluck('nama_sls', 'kode_sls');

        return response()->json(['status' => 'success', 'data' => $rows]);
    }

    public function bs(Request $request): JsonResponse
    {
        $rows = BlokSensus::where('kode_sls', $request->kode_sls)
            ->pluck('nama_bs', 'kode_bs');

        return response()->json(['status' => 'success', 'data' => $rows]);
    }
}
