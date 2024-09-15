<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPenambahan extends Model
{
    use HasFactory;

    protected $fillable = ['barang_id', 'jumlah', 'keterangan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
