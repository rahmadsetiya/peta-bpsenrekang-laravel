<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('kode_desa', 20)->unique();
            $table->string('nama_desa', 100)->nullable();
            $table->float('luas')->nullable();
            $table->string('kode_prov', 10)->nullable();
            $table->string('kode_kabupaten', 10)->nullable();
            $table->string('kode_kecamatan', 10)->nullable();
            $table->string('nama_provinsi', 100)->nullable();
            $table->string('nama_kabupaten', 100)->nullable();
            $table->string('nama_kecamatan', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
