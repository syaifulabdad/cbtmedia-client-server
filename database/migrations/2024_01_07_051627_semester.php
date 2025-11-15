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
        Schema::create('semester', function (Blueprint $table) {
            $table->id();
            $table->string('dapodik_id', 36)->nullable()->index();
            $table->uuid('sekolah_id')->nullable()->index();
            $table->string('tahun_ajaran_id')->nullable()->index();
            $table->string('tahun_ajaran')->index();
            $table->integer('semester_id')->nullable()->index();
            $table->string('semester')->index();
            $table->string('semester_string')->nullable();
            $table->integer('status')->default(0)->index();

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
        Schema::dropIfExists('semester');
    }
};
