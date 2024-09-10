<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = ['barang_id', 'jumlah_keluar', 'tanggal_keluar'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
