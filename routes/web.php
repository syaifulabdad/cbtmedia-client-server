<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\StatusUjianController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Client\UjianController;
use App\Http\Controllers\ResetPesertaController;
use App\Http\Controllers\TarikData\CbtMediaConf;
use App\Http\Controllers\StatusPesertaUjianController;
use App\Http\Controllers\TarikData\TarikDataController;
use App\Http\Controllers\TarikData\SettingTokenController;

Route::get('/', [LoginController::class, 'index']);
// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login/proses', 'proses');
    Route::post('logout', 'logout')->name('logout');
    Route::get('logout', 'logout');
});

// panel
Route::middleware(['auth'])->group(function () {

    Route::middleware(['isAdmin'])->group(function () {
        Route::auto('dashboard', DashboardController::class);
        Route::auto('profile', UserProfileController::class);
        Route::auto('api-token', ApiTokenController::class);
        Route::auto('setting-api', SettingTokenController::class);
        Route::get('cekApiServer', [CbtMediaConf::class, 'cekKoneksi'])->name('cekApiServer');
        Route::auto('tarik-data', TarikDataController::class);

        Route::auto('status-ujian', StatusUjianController::class);
        Route::auto('status-peserta-ujian', StatusPesertaUjianController::class);
        Route::auto('reset-peserta', ResetPesertaController::class);

        Route::auto('user-proktor', UserController::class);
        Route::auto('user-pengawas', UserController::class);
        Route::auto('user-siswa', UserController::class);
    });
});

// client
Route::middleware('isPeserta')->group(function () {
    Route::auto('home', HomeController::class);
    Route::auto('ujian', UjianController::class);
});


// Grup route untuk perintah Artisan
Route::prefix('artisan')->group(function () {
    Route::get('full-reset', function () {
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed --class=RefSeeder');
        Artisan::call('storage:link');
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('event:clear');

        return 'Migrate berhasil dijalankan!';
    });
    // Route::get('migrate', function () {
    //     Artisan::call('migrate:fresh');
    //     Artisan::call('db:seed --class=RefSeeder');
    //     return 'Migrate berhasil dijalankan!';
    // });
    // Route::get('storage-link', function () {
    //     Artisan::call('storage:link');
    //     return 'Storage link berhasil dibuat!';
    // });
    // Route::get('event-clear', function () {
    //     Artisan::call('event:clear');
    //     return 'Event cache berhasil dihapus!';
    // });

    // Route::get('route-clear', function () {
    //     Artisan::call('route:clear');
    //     return 'Route berhasil dihapus!';
    // });
    // Route::get('config-cache', function () {
    //     Artisan::call('config:cache');
    //     return 'Config cache berhasil dibuat!';
    // });
    // Route::get('view-clear', function () {
    //     Artisan::call('view:clear');
    //     return 'View berhasil dihapus!';
    // });
    // Route::get('cache-clear', function () {
    //     Artisan::call('cache:clear');
    //     return 'Cache berhasil dihapus!';
    // });
    // Route::get('optimize', function () {
    //     Artisan::call('optimize');
    //     return 'Optimize berhasil dijalankan!';
    // });
});
