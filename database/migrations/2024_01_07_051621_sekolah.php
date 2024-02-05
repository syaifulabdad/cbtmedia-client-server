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
        Schema::create('sekolah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('dapodik_id')->nullable()->index();
            $table->string('layanan_id', 36)->nullable()->index();
            $table->string('nama')->index('nama_sekolah');
            $table->string('npsn')->index('npsn');
            $table->enum('jenjang', ['PAUD', 'SD', 'SMP', 'SMA'])->index('jenjang');
            $table->string('status_sekolah')->nullable()->index();
            $table->text('alamat')->nullable();
            $table->integer('rt')->nullable();
            $table->integer('rw')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('koordinat')->nullable();
            $table->string('dusun')->nullable();
            $table->string('desa_kelurahan_id', 36)->nullable();
            $table->string('kecamatan_id', 36)->nullable();
            $table->string('kabupaten_id', 36)->nullable();
            $table->string('provinsi_id', 36)->nullable();
            $table->string('email')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('nomor_fax')->nullable();
            $table->string('website')->nullable();
            $table->string('domain')->nullable()->index();
            $table->text('logo_sekolah')->nullable();
            $table->text('logo_daerah')->nullable();
            $table->string('tempat_ttd')->nullable();
            $table->string('timezone')->nullable();

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
        Schema::dropIfExists('sekolah');
    }
};
