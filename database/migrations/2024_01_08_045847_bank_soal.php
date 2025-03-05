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
        Schema::create('bank_soal', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ptk_id')->nullable()->index();
            $table->uuid('nama_ptk')->nullable()->index();

            $table->uuid('ujian_id')->nullable()->index();
            $table->string('mata_pelajaran_id', 36)->nullable()->index();
            $table->string('mapel_pilihan')->nullable()->index();
            $table->text('rombongan_belajar_id')->nullable();
            $table->string('jurusan_id')->nullable()->index();
            $table->integer('tingkat_id')->nullable()->index();
            $table->string('tingkat')->nullable()->index();
            $table->integer('alokasi_waktu')->nullable();
            $table->integer('jumlah_soal_ditampilkan')->nullable();
            $table->string('editor_soal')->nullable();

            $table->decimal('bobot_soal_pilihan')->nullable();
            $table->decimal('bobot_soal_isian_singkat')->nullable();
            $table->decimal('bobot_soal_uraian')->nullable();
            $table->decimal('bobot_soal_menjodohkan')->nullable();

            $table->integer('status')->default(1);

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
        Schema::dropIfExists('bank_soal');
    }
};
