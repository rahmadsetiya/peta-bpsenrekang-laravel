<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opsi_kegiatan', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('kode_kegiatan', 50)->unique();
            $table->string('nama_kegiatan', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opsi_kegiatan');
    }
};
