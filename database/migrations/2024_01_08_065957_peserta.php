<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ujian_id')->index();
            $table->uuid('ruang_id')->nullable()->index();
            $table->uuid('nama_ruang')->nullable()->index();

            $table->uuid('anggota_rombel_id')->nullable()->index();
            $table->uuid('peserta_didik_id')->nullable()->index();
            $table->uuid('rombongan_belajar_id')->nullable()->index();
            $table->uuid('nama_rombel')->nullable()->index();
            $table->uuid('jurusan_id')->nullable()->index();
            $table->uuid('nama_jurusan')->nullable()->index();
            $table->integer('tingkat_id')->nullable()->index();
            $table->string('tingkat')->nullable()->index();

            $table->string('nama')->nullable()->index();
            $table->string('nama_peserta')->nullable()->index();
            $table->string('jenis_kelamin')->nullable()->index();
            $table->string('nik')->nullable()->index();
            $table->string('nis')->nullable()->index();
            $table->string('nisn')->nullable()->index();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable()->index();
            $table->string('agama_id')->nullable()->index();
            $table->string('agama')->nullable()->index();
            $table->text('alamat')->nullable();
            $table->text('foto')->nullable();
            $table->integer('status')->default(1)->index();

            $table->string('username')->nullable();
            $table->string('password')->nullable();

            $table->integer('sesi_hari_1')->nullable()->index();
            $table->integer('sesi_hari_2')->nullable()->index();
            $table->integer('sesi_hari_3')->nullable()->index();
            $table->integer('sesi_hari_4')->nullable()->index();
            $table->integer('sesi_hari_5')->nullable()->index();
            $table->integer('sesi_hari_6')->nullable()->index();
            $table->integer('sesi_hari_7')->nullable()->index();
            $table->integer('sesi_hari_8')->nullable()->index();
            $table->integer('sesi_hari_9')->nullable()->index();
            $table->integer('sesi_hari_10')->nullable()->index();
            $table->integer('sesi_hari_11')->nullable()->index();
            $table->integer('sesi_hari_12')->nullable()->index();
            $table->integer('sesi_hari_13')->nullable()->index();
            $table->integer('sesi_hari_14')->nullable()->index();
            $table->integer('sesi_hari_15')->nullable()->index();
            $table->integer('sesi_hari_16')->nullable()->index();
            $table->integer('sesi_hari_17')->nullable()->index();
            $table->integer('sesi_hari_18')->nullable()->index();
            $table->integer('sesi_hari_19')->nullable()->index();
            $table->integer('sesi_hari_20')->nullable()->index();

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
        Schema::dropIfExists('peserta');
    }
};
