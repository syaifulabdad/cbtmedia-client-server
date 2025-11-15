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
        Schema::create('ruang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sekolah_id')->index();
            $table->uuid('ujian_id')->index();
            $table->uuid('server_id')->nullable()->index();
            $table->string('nama')->index();
            $table->string('nama_ruang')->index()->nullable();
            $table->string('host')->nullable()->index();
            $table->string('mac_address')->nullable()->index();
            $table->string('username')->nullable()->index();
            $table->string('password')->nullable()->index();
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
        Schema::dropIfExists('ruang');
    }
};
