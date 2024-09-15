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
Route::get('/riwayatPenambahan', [BarangController::class, 'riwayatPenambahan'])->name('riwayats');
Route::get('/search', [BarangController::class, 'search'])->name('barang.search');
Route::get('/cari', [BarangController::class, 'cari'])->name('cari');
Route::delete('/riwayat/hapus-terpilih', [BarangController::class, 'hapusTerpilihRiwayat'])->name('riwayat.hapusTerpilih');
