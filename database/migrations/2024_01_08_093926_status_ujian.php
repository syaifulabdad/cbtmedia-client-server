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
        Schema::create('status_ujian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ujian_id')->index();
            $table->text('ruang_id')->nullable();
            $table->uuid('jadwal_id')->nullable()->index();

            $table->uuid('bank_soal_id')->index();
            $table->integer('jumlah_soal')->nullable();
            $table->integer('jumlah_soal_ditampilkan')->nullable();
            $table->uuid('ptk_id')->nullable();

            $table->integer('tingkat_id')->nullable()->index();
            $table->string('jurusan_id', 225)->nullable()->index();
            $table->text('rombongan_belajar_id')->nullable();
            $table->integer('hari_ke')->nullable()->index();
            $table->integer('sesi_ke')->nullable()->index();

            $table->dateTime('waktu_ujian');
            $table->date('tanggal');
            $table->time('jam');
            $table->string('hari')->nullable();
            $table->integer('tgl')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('bulan_string')->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('tahun_string')->nullable();

            $table->string('mode_waktu')->default('waktu-admin');
            $table->integer('alokasi_waktu_soal')->nullable();
            $table->integer('alokasi_waktu_peserta')->nullable();
            $table->integer('batas_masuk')->default(0);
            $table->integer('waktu_minimal')->default(0);
            $table->dateTime('waktu_terlambat');
            $table->dateTime('waktu_selesai_soal');
            $table->dateTime('waktu_selesai_peserta');
            $table->string('token', 36)->nullable();
            $table->integer('status')->default(1);

            $table->string('operator')->nullable();
            $table->string('nip_operator', 20)->nullable();
            $table->string('pengawas')->nullable();
            $table->string('nip_pengawas', 20)->nullable();
            $table->text('catatan')->nullable();
            $table->integer('jumlah_peserta')->nullable();
            $table->integer('jumlah_peserta_hadir')->nullable();
            $table->integer('jumlah_peserta_tidak_hadir')->nullable();
            $table->text('peserta_hadir')->nullable();
            $table->text('peserta_tidak_hadir')->nullable();

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
        Schema::dropIfExists('status_ujian');
    }
};
