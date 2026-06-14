<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KategoriController;
// use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\MemberController;

// 1. Halaman Depan Aplikasi
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard Utama (Multi-role Landing)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Manajemen Profil Pengguna (Bawaan Breeze/Jetstream)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');
    Route::get('/buku/{id}/read', [BukuController::class, 'read'])->name('buku.read');
    Route::post('/buku/{id}/bookmark', [BukuController::class, 'toggleBookmark'])->name('buku.bookmark');
    Route::post('/buku/{id}/ulasan', [\App\Http\Controllers\UlasanController::class, 'store'])->name('buku.ulasan.store');
    Route::get('/katalog', [\App\Http\Controllers\KatalogController::class, 'index'])->name('member.katalog');
    Route::get('/tersimpan', [\App\Http\Controllers\FavoritController::class, 'index'])->name('member.tersimpan');
    Route::delete('/tersimpan/clear', [\App\Http\Controllers\FavoritController::class, 'clear'])->name('member.tersimpan.clear');
    Route::post('/buku/{id}/favorit', [\App\Http\Controllers\FavoritController::class, 'toggle'])->name('buku.favorit.toggle');
    Route::post('/buku/{id}/pinjam', [\App\Http\Controllers\PeminjamanController::class, 'store'])->name('buku.pinjam');
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'memberPeminjaman'])->name('member.peminjaman.index');
    Route::get('/riwayat', [\App\Http\Controllers\PeminjamanController::class, 'memberRiwayat'])->name('member.riwayat.index');
    Route::post('/peminjaman/{id}/kembali', [\App\Http\Controllers\PeminjamanController::class, 'kembali'])->name('member.peminjaman.kembali');
    Route::post('/peminjaman/{id}/cancel', [\App\Http\Controllers\PeminjamanController::class, 'cancelRequest'])->name('member.peminjaman.cancel');
    
    // Member Reservasi Routes
    Route::get('/reservasi', [\App\Http\Controllers\ReservasiController::class, 'index'])->name('member.reservasi.index');
    Route::post('/buku/{id}/reservasi', [\App\Http\Controllers\ReservasiController::class, 'store'])->name('buku.reservasi');
    Route::post('/reservasi/{id}/cancel', [\App\Http\Controllers\ReservasiController::class, 'cancel'])->name('member.reservasi.cancel');
    
    // Member Settings Routes
    Route::get('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('member.pengaturan.index');
    Route::post('/pengaturan/update-name', [\App\Http\Controllers\PengaturanController::class, 'updateName'])->name('member.pengaturan.updateName');
    Route::post('/pengaturan/update-email', [\App\Http\Controllers\PengaturanController::class, 'updateEmail'])->name('member.pengaturan.updateEmail');
    Route::post('/pengaturan/update-phone', [\App\Http\Controllers\PengaturanController::class, 'updatePhone'])->name('member.pengaturan.updatePhone');
    Route::post('/pengaturan/update-password', [\App\Http\Controllers\PengaturanController::class, 'updatePassword'])->name('member.pengaturan.updatePassword');
    Route::post('/pengaturan/update-foto', [\App\Http\Controllers\PengaturanController::class, 'updateFoto'])->name('member.pengaturan.updateFoto');
    Route::post('/pengaturan/delete-foto', [\App\Http\Controllers\PengaturanController::class, 'deleteFoto'])->name('member.pengaturan.deleteFoto');
    Route::post('/pengaturan/update-notifikasi', [\App\Http\Controllers\PengaturanController::class, 'updateNotifikasi'])->name('member.pengaturan.updateNotifikasi');
    Route::get('/lang/{locale}', [\App\Http\Controllers\PengaturanController::class, 'changeLanguage'])->name('lang.switch');
    Route::get('/theme/{theme}', [\App\Http\Controllers\PengaturanController::class, 'changeTheme'])->name('theme.switch');
    Route::get('/notifications/read-all', [\App\Http\Controllers\PengaturanController::class, 'readAllNotifications'])->name('notifications.readAll');
    Route::get('/panduan', [\App\Http\Controllers\PanduanController::class, 'index'])->name('member.panduan.index');
});

// 4. Area Khusus Administrator
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('pustakawan', \App\Http\Controllers\PustakawanController::class);
    Route::delete('buku/destroy-multiple', [BukuController::class, 'destroyMultiple'])->name('buku.destroy-multiple');
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class); 
    Route::resource('member', MemberController::class);
    Route::get('peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'pustakawanPeminjaman'])->name('peminjaman.index');
    Route::get('pengembalian', [\App\Http\Controllers\PeminjamanController::class, 'pustakawanPengembalian'])->name('pengembalian.index');
    Route::get('denda', [\App\Http\Controllers\DendaController::class, 'index'])->name('denda.index');
    Route::post('denda/{id}/bayar', [\App\Http\Controllers\DendaController::class, 'bayar'])->name('denda.bayar');
    Route::post('peminjaman/{id}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::post('peminjaman/{id}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    
    // Laporan Routes
    Route::get('laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [\App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
    Route::get('laporan/print', [\App\Http\Controllers\LaporanController::class, 'print'])->name('laporan.print');
    
    // Kunjungan Routes
    Route::resource('kunjungan', \App\Http\Controllers\KunjunganController::class)->only(['index', 'store']);
});

// 5. Area Khusus Pustakawan MacaBae (CRUD Buku Diringkas Sempurna)
Route::middleware(['auth', 'role:pustakawan'])->prefix('pustakawan')->name('pustakawan.')->group(function () {
    Route::delete('buku/destroy-multiple', [BukuController::class, 'destroyMultiple'])->name('buku.destroy-multiple');
    Route::resource('buku', BukuController::class);
    Route::resource('kategori', KategoriController::class); 
    Route::resource('member', MemberController::class);
    Route::get('peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'pustakawanPeminjaman'])->name('peminjaman.index');
    Route::get('pengembalian', [\App\Http\Controllers\PeminjamanController::class, 'pustakawanPengembalian'])->name('pengembalian.index');
    Route::get('denda', [\App\Http\Controllers\DendaController::class, 'index'])->name('denda.index');
    Route::post('denda/{id}/bayar', [\App\Http\Controllers\DendaController::class, 'bayar'])->name('denda.bayar');
    Route::post('peminjaman/{id}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::post('peminjaman/{id}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    
    // Kunjungan Routes
    Route::resource('kunjungan', \App\Http\Controllers\KunjunganController::class)->only(['index', 'store']);
});

// 6. Area Khusus Member/Pembaca Terdaftar
// Route::middleware(['auth', 'role:member'])->group(function () {
//     Route::get('/beranda', [MemberDashboardController::class, 'index'])->name('member.dashboard');
// });

require __DIR__.'/auth.php';