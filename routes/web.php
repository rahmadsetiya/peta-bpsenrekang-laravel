<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BlokSensusController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KelolaPetaWilkerstatController;
use App\Http\Controllers\OpsiKegiatanController;
use App\Http\Controllers\SlsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/preview-peta/{filename}', [KelolaPetaWilkerstatController::class, 'preview'])
    ->where('filename', '.*')
    ->name('preview-peta');

// Auth
require __DIR__ . '/auth.php';

// API (public geographic data)
Route::prefix('api')->group(function () {
    Route::get('/kecamatan', [ApiController::class, 'kecamatan'])->name('api.kecamatan');
    Route::get('/desa', [ApiController::class, 'desa'])->name('api.desa');
    Route::get('/sls', [ApiController::class, 'sls'])->name('api.sls');
    Route::get('/bs', [ApiController::class, 'bs'])->name('api.bs');
});

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only
    Route::middleware('role:ADMIN')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Admin + IPDS only
    Route::middleware('role:ADMIN,IPDS')->group(function () {
        Route::resource('opsi-kegiatan', OpsiKegiatanController::class)->except(['show']);
        Route::resource('blok-sensus', BlokSensusController::class)->except(['show']);
        Route::get('/blok-sensus/{blokSensu}/detail', [BlokSensusController::class, 'detail'])->name('blok-sensus.detail');
        Route::get('/blok-sensus/export-excel', [BlokSensusController::class, 'exportExcel'])->name('blok-sensus.export');
        Route::post('/blok-sensus/import-excel', [BlokSensusController::class, 'importExcel'])->name('blok-sensus.import');

        Route::resource('sls', SlsController::class)->except(['show']);
        Route::get('/sls/{sl}/detail', [SlsController::class, 'detail'])->name('sls.detail');
        Route::get('/sls/export-excel', [SlsController::class, 'exportExcel'])->name('sls.export');
        Route::post('/sls/import-excel', [SlsController::class, 'importExcel'])->name('sls.import');

        Route::resource('desa', DesaController::class)->except(['show']);
        Route::get('/desa/{desa}/detail', [DesaController::class, 'detail'])->name('desa.detail');
        Route::get('/desa/export-excel', [DesaController::class, 'exportExcel'])->name('desa.export');
        Route::post('/desa/import-excel', [DesaController::class, 'importExcel'])->name('desa.import');

        // Map management
        Route::get('/kelola-peta/{kegiatan}', [KelolaPetaWilkerstatController::class, 'index'])->name('kelola-peta.index');
        Route::post('/kelola-peta/upload', [KelolaPetaWilkerstatController::class, 'upload'])->name('kelola-peta.upload');
        Route::post('/kelola-peta/batch-upload', [KelolaPetaWilkerstatController::class, 'batchUpload'])->name('kelola-peta.batch-upload');
        Route::get('/kelola-peta/download/{peta}', [KelolaPetaWilkerstatController::class, 'download'])->name('kelola-peta.download');
        Route::get('/kelola-peta/download-all/{kegiatan}', [KelolaPetaWilkerstatController::class, 'downloadAll'])->name('kelola-peta.download-all');
        Route::delete('/kelola-peta/{peta}', [KelolaPetaWilkerstatController::class, 'destroy'])->name('kelola-peta.destroy');
        Route::post('/kelola-peta/{peta}/replace', [KelolaPetaWilkerstatController::class, 'replace'])->name('kelola-peta.replace');
        Route::post('/kelola-peta/{peta}/rename', [KelolaPetaWilkerstatController::class, 'rename'])->name('kelola-peta.rename');
    });

    // All authenticated users can manage / view kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/{kegiatan}/detail', [KegiatanController::class, 'detail'])->name('kegiatan.detail');
    Route::middleware('role:ADMIN,IPDS,SUBJECT_MATTER')->group(function () {
        Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
        Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
        Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
        Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
        Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    });
});
