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
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ptk_id')->nullable()->index();

            $table->uuid('ujian_id')->nullable()->index();
            $table->uuid('bank_soal_id')->index();
            $table->integer('urutan')->default(1);
            $table->string('jenis_soal')->default('pilihan');
            $table->string('kd', 10)->nullable();
            $table->text('soal_teks')->nullable();
            $table->text('soal_gambar')->nullable();
            $table->string('posisi_gambar', 10)->nullable()->enum(['atas', 'bawah']);
            $table->text('soal_audio')->nullable();
            $table->string('posisi_audio', 10)->nullable()->enum(['atas', 'bawah']);
            $table->text('soal_video')->nullable();
            $table->string('posisi_video', 10)->nullable()->enum(['atas', 'bawah']);
            $table->text('jawaban_1')->nullable();
            $table->text('jawaban_2')->nullable();
            $table->text('jawaban_3')->nullable();
            $table->text('jawaban_4')->nullable();
            $table->text('jawaban_5')->nullable();
            $table->string('kunci_jawaban')->nullable();
            $table->integer('acak_soal')->default(1);
            $table->integer('acak_jawaban')->default(1);

            $table->integer('status')->default(1)->index();

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
        Schema::dropIfExists('soal');
    }
};
