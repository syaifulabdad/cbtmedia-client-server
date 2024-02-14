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
        Schema::create('ptk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('dapodik_id')->nullable()->index();
            $table->uuid('sekolah_id')->index();
            $table->string('nama')->index();
            $table->string('jenis_kelamin')->nullable();
            $table->string('nik')->nullable()->index();
            $table->string('nuptk')->nullable()->index();
            $table->string('nip')->nullable()->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable()->index();
            $table->string('agama_id')->nullable()->index();
            $table->text('alamat')->nullable();
            $table->string('jenis_ptk_id')->nullable();
            $table->string('jenis_ptk')->nullable();
            $table->string('password')->nullable();
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
        Schema::dropIfExists('ptk');
    }
};
