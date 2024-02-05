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
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->string('id', 36)->index();
            $table->string('dapodik_id', 36)->nullable()->index();
            $table->uuid('sekolah_id')->nullable()->index();
            $table->string('nama')->index();
            $table->string('kode')->nullable();
            $table->string('kelompok')->nullable();
            $table->integer('mapel_agama')->default(0);
            $table->integer('urutan')->nullable();
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
        Schema::dropIfExists('mata_pelajaran');
    }
};
