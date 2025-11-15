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
        Schema::create('tarik_data', function (Blueprint $table) {
            $table->id();
            $table->uuid('sekolah_id')->nullable()->index();
            $table->string('nama');
            $table->string('host')->nullable();
            $table->text('token')->nullable();
            $table->dateTime('tarik_data_terakhir')->nullable();

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
        Schema::dropIfExists('tarik_data');
    }
};
