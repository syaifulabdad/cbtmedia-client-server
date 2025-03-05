<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CbtServer\CbtMediaConf;
use App\Http\Controllers\CbtServer\SettingTokenController;
use App\Http\Controllers\CbtServer\TarikDataController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResetPesertaController;
use App\Http\Controllers\StatusPesertaUjianController;
use App\Http\Controllers\StatusUjianController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index']);
// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login/proses', 'proses');
    Route::post('logout', 'logout')->name('logout');
    Route::get('logout', 'logout');
});

// panel
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

// client
Route::middleware('isPeserta')->group(function () {
    Route::auto('home', HomeController::class);
});
