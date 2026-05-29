<?php

namespace App\Http\Controllers;

use App\Models\BlokSensus;
use App\Models\Desa;
use App\Models\Kegiatan;
use App\Models\KegiatanWilkerstatPeta;
use App\Models\Sls;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = strtolower($user->role);

        $stats = [
            'total_kegiatan' => Kegiatan::count(),
            'total_peta' => KegiatanWilkerstatPeta::whereNull('id_parent_peta')->count(),
            'total_users' => User::count(),
            'total_blok_sensus' => BlokSensus::count(),
            'total_sls' => Sls::count(),
            'total_desa' => Desa::count(),
        ];

        $statusCounts = Kegiatan::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'statusCounts' => $statusCounts,
            'role' => $role,
        ]);
    }
}
