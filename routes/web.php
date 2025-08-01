<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BarangController::class, 'index'])->name('dashboard');
Route::get('/panduan', [BarangController::class, 'panduan'])->name('panduans');
Route::get('/barang', [BarangController::class, 'manage'])->name('barang.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
Route::post('/barang/update-stock-multi', [BarangController::class, 'updateStockMulti'])->name('barang.updateStockMulti');
Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
Route::patch('/barang/{barang}/update-stock', [BarangController::class, 'updateStock'])->name('barang.updateStock');
Route::get('/riwayat/export', [BarangController::class, 'exportRiwayat'])->name('riwayat.export');
Route::get('/riwayat', [BarangController::class, 'riwayat'])->name('riwayat.index');
Route::get('/riwayatPenambahan', [BarangController::class, 'riwayatPenambahan'])->name('riwayats');
Route::get('/search', [BarangController::class, 'search'])->name('barang.search');
Route::get('/cari', [BarangController::class, 'cari'])->name('cari');
Route::get('/search', [BarangController::class, 'search'])->name('barang.search');
Route::delete('/riwayat/hapus-terpilih', [BarangController::class, 'hapusTerpilihRiwayat'])->name('riwayat.hapusTerpilih');
Route::delete('/riwayat/hapus-terpilih-penambahan', [BarangController::class, 'hapusTerpilihPenambahan'])
    ->name('riwayat.hapusTerpilihPenambahan');
