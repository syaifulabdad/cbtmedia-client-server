<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('layanan_id')->nullable()->index();
            $table->uuid('sekolah_id')->nullable()->index();
            $table->uuid('ptk_id')->nullable()->index();
            $table->uuid('peserta_id')->nullable()->index();
            $table->uuid('pengawas_id')->nullable()->index();

            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('username')->nullable()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->text('avatar')->nullable();
            $table->text('google_id')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('type')->nullable();
            $table->string('role')->nullable();
            $table->string('status')->nullable();

            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        User::create(['name' => 'Administrator', 'email' => 'admin@cbtmedia.com', 'password' => Hash::make('12345'), 'type' => 'admin', 'status' => 'active', 'email_verified_at' => '2022-01-02 17:04:58', 'created_at' => now(),]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
