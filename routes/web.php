<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MutasiAsetController;
use App\Http\Controllers\PemeliharaanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;

// ========== AUTH ==========
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== SEMUA USER LOGIN ==========
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard',     [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api', [DashboardController::class, 'api'])->name('dashboard.api');

    // ---- ASET ----
    Route::get('/aset',        [AsetController::class, 'index'])->name('aset.index');
    Route::get('/scan',        [AsetController::class, 'scan'])->name('aset.scan');

    // Admin-only (create)
    Route::middleware('role:admin')->group(function () {
        Route::get('/aset/create',  [AsetController::class, 'create'])->name('aset.create');
        Route::post('/aset',        [AsetController::class, 'store'])->name('aset.store');
    });

    // Route dengan parameter (harus setelah create)
    Route::get('/aset/{aset}',          [AsetController::class, 'show'])->name('aset.show');
    Route::get('/aset/{aset}/qrcode',   [AsetController::class, 'qrcode'])->name('aset.qrcode');

    Route::middleware('role:admin')->group(function () {
        Route::get('/aset/{aset}/edit', [AsetController::class, 'edit'])->name('aset.edit');
        Route::put('/aset/{aset}',      [AsetController::class, 'update'])->name('aset.update');
        Route::delete('/aset/{aset}',   [AsetController::class, 'destroy'])->name('aset.destroy');
    });

    // ---- MUTASI ----
    Route::get('/mutasi', [MutasiAsetController::class, 'index'])->name('mutasi.index');

    Route::middleware('role:admin')->group(function () {
        Route::get('/mutasi/create', [MutasiAsetController::class, 'create'])->name('mutasi.create');
        Route::post('/mutasi',       [MutasiAsetController::class, 'store'])->name('mutasi.store');
    });

    Route::get('/mutasi/{mutasi}', [MutasiAsetController::class, 'show'])->name('mutasi.show');

    // ---- PEMELIHARAAN ----
    Route::get('/pemeliharaan', [PemeliharaanController::class, 'index'])->name('pemeliharaan.index');

    Route::middleware('role:admin')->group(function () {
        Route::get('/pemeliharaan/create', [PemeliharaanController::class, 'create'])->name('pemeliharaan.create');
        Route::post('/pemeliharaan',       [PemeliharaanController::class, 'store'])->name('pemeliharaan.store');
    });

    Route::get('/pemeliharaan/{pemeliharaan}', [PemeliharaanController::class, 'show'])->name('pemeliharaan.show');

    Route::middleware('role:admin')->group(function () {
        Route::get('/pemeliharaan/{pemeliharaan}/edit', [PemeliharaanController::class, 'edit'])->name('pemeliharaan.edit');
        Route::put('/pemeliharaan/{pemeliharaan}',      [PemeliharaanController::class, 'update'])->name('pemeliharaan.update');
        Route::delete('/pemeliharaan/{pemeliharaan}',   [PemeliharaanController::class, 'destroy'])->name('pemeliharaan.destroy');
    });

    // ---- LAPORAN ----
    Route::get('/laporan',            [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/inventaris', [LaporanController::class, 'inventaris'])->name('laporan.inventaris');
    Route::get('/laporan/notifikasi', [LaporanController::class, 'notifikasi'])->name('laporan.notifikasi');

    // ---- MASTER DATA ----
    Route::get('/kategori',   [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/departemen', [DepartemenController::class, 'index'])->name('departemen.index');
    Route::get('/karyawan',   [KaryawanController::class, 'index'])->name('karyawan.index');

    Route::middleware('role:admin')->group(function () {
        Route::post('/kategori',              [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{kategori}',    [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::post('/departemen',               [DepartemenController::class, 'store'])->name('departemen.store');
        Route::put('/departemen/{departemen}',   [DepartemenController::class, 'update'])->name('departemen.update');
        Route::delete('/departemen/{departemen}',[DepartemenController::class, 'destroy'])->name('departemen.destroy');

        Route::post('/karyawan',             [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::put('/karyawan/{karyawan}',   [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{karyawan}',[KaryawanController::class, 'destroy'])->name('karyawan.destroy');

        Route::resource('users', UserController::class);
    });
});