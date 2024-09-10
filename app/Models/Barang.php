<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['nama_barang', 'stok', 'gambar'];
    public function keranjang()
    {
        return $this->hasMany(Keranjang::class);
    }
}
