<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/tools', function () {
        return "Selamat datang Admin! Anda punya akses teknis tinggi."; 
    });
});

Route::middleware(['auth', 'role:pustakawan'])->group(function () {
    Route::get('/pustakawan/kelola-buku', [BukuController::class, 'index'])->name('pustakawan.buku.index');
    Route::get('/pustakawan/kelola-buku/tambah', [BukuController::class, 'create'])->name('pustakawan.buku.create');
    Route::post('/pustakawan/kelola-buku', [BukuController::class, 'store'])->name('pustakawan.buku.store');
    
    // Rute Baru
    Route::get('/pustakawan/kelola-buku/{id}/edit', [BukuController::class, 'edit'])->name('pustakawan.buku.edit');
    Route::put('/pustakawan/kelola-buku/{id}', [BukuController::class, 'update'])->name('pustakawan.buku.update');
    Route::delete('/pustakawan/kelola-buku/{id}', [BukuController::class, 'destroy'])->name('pustakawan.buku.destroy');
});

Route::middleware(['auth', 'role:member'])->group(function () {
    Route::post('/pinjam/{bukuId}', [PeminjamanController::class, 'store'])->name('pinjam.buku');
});

require __DIR__.'/auth.php';