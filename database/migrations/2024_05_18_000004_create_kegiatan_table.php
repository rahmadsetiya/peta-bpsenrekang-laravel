<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_prov', 2)->nullable();
            $table->string('nama_provinsi')->nullable();
            $table->string('kode_kabupaten', 4)->nullable();
            $table->string('nama_kabupaten')->nullable();
            $table->string('kode_kecamatan', 6)->nullable();
            $table->string('nama_kecamatan')->nullable();
            $table->timestamps();
        });

        Schema::create('kegiatan', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('id_opsi_kegiatan', 36)->nullable();
            $table->string('kode_opsi_kegiatan', 50)->nullable();
            $table->char('id_user', 36)->nullable();
            $table->integer('tahun')->nullable();
            $table->string('bulan', 20)->nullable();
            $table->date('tanggal_batas_cetak')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_opsi_kegiatan')->references('id')->on('opsi_kegiatan')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('kegiatan_blok_sensus', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('id_kegiatan', 36)->nullable();
            $table->char('id_blok_sensus', 36)->nullable();
            $table->foreign('id_kegiatan')->references('id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('id_blok_sensus')->references('id')->on('blok_sensus')->onDelete('cascade');
        });

        Schema::create('kegiatan_sls', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('id_kegiatan', 36)->nullable();
            $table->char('id_sls', 36)->nullable();
            $table->foreign('id_kegiatan')->references('id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('id_sls')->references('id')->on('sls')->onDelete('cascade');
        });

        Schema::create('kegiatan_desa', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('id_kegiatan', 36)->nullable();
            $table->char('id_desa', 36)->nullable();
            $table->foreign('id_kegiatan')->references('id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('id_desa')->references('id')->on('desa')->onDelete('cascade');
        });

        Schema::create('kegiatan_wilkerstat_peta', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('id_kegiatan', 36)->nullable();
            $table->enum('wilkerstat_type', ['blok_sensus', 'sls', 'desa'])->nullable();
            $table->char('id_wilkerstat', 36)->nullable();
            $table->enum('jenis_peta', ['dengan_titik', 'tanpa_titik'])->nullable();
            $table->char('id_parent_peta', 36)->nullable();
            $table->string('file_path')->nullable();
            $table->string('nama_file')->nullable();
            $table->dateTime('uploaded_at')->nullable();
            $table->string('uploader', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_kegiatan')->references('id')->on('kegiatan')->onDelete('cascade');
            $table->foreign('id_parent_peta')->references('id')->on('kegiatan_wilkerstat_peta')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_wilkerstat_peta');
        Schema::dropIfExists('kegiatan_desa');
        Schema::dropIfExists('kegiatan_sls');
        Schema::dropIfExists('kegiatan_blok_sensus');
        Schema::dropIfExists('kegiatan');
        Schema::dropIfExists('kecamatan');
    }
};
