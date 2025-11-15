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
        Schema::create('ujian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id')->index();
            $table->string('nama')->index();
            $table->string('nama_ujian')->index()->nullable();
            $table->integer('semester_id')->index();
            $table->date('tanggal')->nullable();
            $table->integer('tahun')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('jumlah_hari')->default(1);
            $table->integer('jumlah_sesi')->default(1);
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
        Schema::dropIfExists('ujian');
    }
};
