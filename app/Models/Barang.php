<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['nama_barang', 'stok', 'gambar'];
     // Relasi ke RiwayatPenambahan
     public function riwayatPenambahans()
     {
         return $this->hasMany(RiwayatPenambahan::class);
     }
}
