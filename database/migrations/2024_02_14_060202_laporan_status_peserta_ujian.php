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
        Schema::create('laporan_status_peserta_ujian', function (Blueprint $table) {
            $table->id();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ujian_id')->index();
            $table->uuid('ruang_id')->nullable()->index();
            $table->uuid('bank_soal_id')->index();
            $table->uuid('status_ujian_id')->index();
            $table->uuid('peserta_id')->index();

            $table->integer('tingkat_id')->nullable()->index();
            $table->string('jurusan_id', 36)->nullable()->index();
            $table->uuid('rombongan_belajar_id')->nullable()->index();

            $table->integer('alokasi_waktu_peserta')->nullable();
            $table->date('tanggal');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_harus_selesai');
            $table->dateTime('waktu_selesai');
            $table->integer('status')->default(1);
            $table->integer('suspend')->default(0);

            $table->integer('jawaban_benar')->default(0);

            $table->string('absen_koordinat')->nullable();
            $table->string('absen_koordinat_lat')->nullable();
            $table->string('absen_koordinat_long')->nullable();
            $table->text('absen_foto')->nullable();

            $table->ipAddress('ip_address')->nullable();
            $table->string('device')->nullable()->index();
            $table->string('browser')->nullable()->index();
            $table->string('idle')->nullable()->index();
            $table->string('hidden')->nullable()->index();
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
        Schema::dropIfExists('laporan_status_peserta_ujian');
    }
};
