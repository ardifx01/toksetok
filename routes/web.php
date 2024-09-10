<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BarangController::class, 'index'])->name('dashboard');
Route::get('/barang', [BarangController::class, 'manage'])->name('barang.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
Route::patch('/barang/{barang}/update-stock', [BarangController::class, 'updateStock'])->name('barang.updateStock');
Route::get('/riwayat/export', [BarangController::class, 'exportRiwayat'])->name('riwayat.export');
Route::get('/riwayat', [BarangController::class, 'riwayat'])->name('riwayat.index');
Route::get('/search', [BarangController::class, 'search'])->name('barang.search');
Route::get('/cari', [BarangController::class, 'cari'])->name('cari');
// Fitur Keranjang
Route::post('/keranjang/{barang}/tambah', [BarangController::class, 'tambahKeKeranjang'])->name('keranjang.tambah');
Route::get('/keranjang', [BarangController::class, 'lihatKeranjang'])->name('keranjang.lihat');
Route::patch('/keranjang/{keranjang}', [BarangController::class, 'updateKeranjang'])->name('keranjang.update');
Route::delete('/keranjang/{keranjang}', [BarangController::class, 'hapusKeranjang'])->name('keranjang.hapus');

// Checkout Keranjang
Route::patch('/checkout', [BarangController::class, 'checkout'])->name('keranjang.checkout');
Route::post('/keranjang/hapus-terpilih', [BarangController::class, 'hapusTerpilih'])->name('keranjang.hapusTerpilih');
// Tambahkan route untuk hapus terpilih pada riwayat
Route::delete('/riwayat/hapus-terpilih', [BarangController::class, 'hapusTerpilihRiwayat'])->name('riwayat.hapusTerpilih');

