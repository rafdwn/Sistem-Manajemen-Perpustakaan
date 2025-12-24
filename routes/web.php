<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

/*profil*/
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');

/*landing*/
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/cetak-info/{id}', [LandingController::class, 'cetakInfo'])->name('cetak.info');

/*login*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*lupa pw*/
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendPassword'])->name('password.send');
Route::get('/password/verify', [AuthController::class, 'verifyPassword'])->name('password.verify');
Route::post('/password/verify', [AuthController::class, 'processVerify'])->name('password.verify.process');

/*resource*/
Route::resource('anggota', AnggotaController::class);
Route::resource('buku', BukuController::class);
Route::resource('peminjaman', PeminjamanController::class);
Route::resource('pengembalian', PengembalianController::class);
Route::resource('denda', DendaController::class);

/*laporan*/
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::post('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

/*anggota*/
Route::get('/anggota-search', [AnggotaController::class, 'search'])->name('anggota.search');

/*buku*/
Route::get('/buku-search', [BukuController::class, 'search'])->name('buku.search');

/*pengembalian*/
Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
Route::delete('/pengembalian/{id}', [PengembalianController::class, 'hapus'])
->name('pengembalian.hapus');
Route::post('/peminjaman/{id}/preview', [PengembalianController::class, 'preview'])
    ->name('pengembalian.preview');
Route::delete('/peminjaman/{id}/konfirmasi', [PengembalianController::class, 'konfirmasi'])
    ->name('pengembalian.konfirmasi');
Route::post('/pengembalian/proses', [PengembalianController::class, 'proses'])
    ->name('pengembalian.proses');
Route::get('/pengembalian/preview/{id}', [PengembalianController::class, 'preview']);

/*peminjaman*/
Route::get('/peminjaman-search', [PeminjamanController::class, 'search'])->name('peminjaman.search');
Route::get('/peminjaman/cetak/{id}', [PeminjamanController::class, 'cetakPdf'])->name('peminjaman.cetak');





