<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('barang')->get();
        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('laporan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required',
            'jumlah_keluar' => 'required|integer',
            'tanggal_keluar' => 'required|date',
        ]);

        Laporan::create($request->all());

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil ditambahkan');
    }

    public function exportExcel()
    {
        // Export laporan ke Excel
    }

    public function exportPdf()
    {
        // Export laporan ke PDF
    }
}
