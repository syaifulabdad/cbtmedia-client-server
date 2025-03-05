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
        Schema::create('peserta_didik', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('dapodik_id')->nullable()->index();
            $table->uuid('sekolah_id')->index();
            $table->string('nama')->index();
            $table->string('nama_peserta_didik')->index()->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('nik')->nullable()->index();
            $table->string('nis')->nullable()->index();
            $table->string('nisn')->nullable()->index();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable()->index();
            $table->string('agama_id')->nullable()->index();
            $table->text('alamat')->nullable();
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
        Schema::dropIfExists('peserta_didik');
    }
};
