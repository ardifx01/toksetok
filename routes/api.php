<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarangController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('barang')->group(function() {
    Route::get('/', [BarangController::class, 'index']); // List all barang
    Route::post('/store', [BarangController::class, 'store']); // Add barang
    Route::put('/update/{id}', [BarangController::class, 'update']); // Update barang
    Route::delete('/delete/{id}', [BarangController::class, 'destroy']); // Delete barang
    Route::post('/take/{id}', [BarangController::class, 'take']); // Take barang
    Route::get('/search', [BarangController::class, 'search']); // Search barang
    Route::get('/riwayat-pengambilan', [BarangController::class, 'riwayatPengambilan']); // List riwayat pengambilan
});