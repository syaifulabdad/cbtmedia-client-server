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
        Schema::create('pengawas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ujian_id')->index();

            $table->string('host')->nullable()->index();
            $table->string('token')->nullable()->index();
            $table->text('token_long')->nullable();
            $table->text('qr_string')->nullable();
            $table->text('qr_file')->nullable();
            $table->integer('status')->default(1)->index();

            $table->string('nama')->nullable()->index();
            $table->string('nama_pengawas')->nullable()->index();
            $table->date('tanggal')->nullable()->index();
            $table->dateTime('tanggal_aktivasi')->nullable()->index();
            $table->string('ip_address')->nullable()->index();


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
        Schema::dropIfExists('pengawas');
    }
};
