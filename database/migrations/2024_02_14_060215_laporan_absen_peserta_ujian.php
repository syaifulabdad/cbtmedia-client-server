<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_absen_peserta_ujian', function (Blueprint $table) {
            $table->id();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ujian_id')->index();

            $table->date('tanggal')->nullable()->index();
            $table->dateTime('waktu')->nullable();

            $table->uuid('peserta_id')->index();
            $table->uuid('status_peserta_ujian_id')->index();
            $table->uuid('status_ujian_id')->index();
            $table->uuid('bank_soal_id')->index();
            $table->uuid('ruang_id')->nullable()->index();
            $table->uuid('pengawas_id')->nullable()->index();
            $table->string('status')->default("H")->index();
            $table->dateTime('kirim_data')->nullable();

            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_absen_peserta_ujian');
    }
};
