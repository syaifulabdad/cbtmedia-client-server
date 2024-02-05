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
        Schema::create('rombongan_belajar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('dapodik_id')->nullable()->index();
            $table->uuid('sekolah_id')->index();
            $table->string('semester_id', 36)->nullable()->index();
            $table->string('tingkat_id')->index();
            $table->string('tingkat');
            $table->string('jurusan_id', 36)->index();
            $table->string('nama')->index();
            $table->uuid('ptk_id')->nullable();

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
        Schema::dropIfExists('rombongan_belajar');
    }
};
