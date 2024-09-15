<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatPengambilan;
use App\Models\RiwayatPenambahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('dashboard', compact('barangs'));
    }

    public function manage(Request $request)
    {
        $sort = $request->query('sort');

        switch ($sort) {
            case 'stok_asc':
                $barangs = Barang::orderBy('stok', 'asc')->get();
                break;
            case 'stok_desc':
                $barangs = Barang::orderBy('stok', 'desc')->get();
                break;
            default:
                $barangs = Barang::all();
                break;
        }

        return view('manage', compact('barangs'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'stok' => 'required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $barang = new Barang();
            $barang->nama_barang = $request->nama_barang;
            $barang->stok = $request->stok;

            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('public/barangs');
                $barang->gambar = basename($path);
            }

            $barang->save();

            RiwayatPenambahan::create([
                'barang_id' => $barang->id,
                'jumlah' => $barang->stok,
                'keterangan' => 'Penambahan stok baru',
            ]);

            alert()->success('Sukses', 'Barang berhasil ditambahkan.');
        } catch (\Exception $e) {
            alert()->error('Error', 'Terjadi kesalahan saat menambahkan barang.');
        }

        return redirect()->route('barang.index');
    }

    public function update(Request $request, Barang $barang)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'stok' => 'required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $stokLama = $barang->stok;
            $barang->nama_barang = $request->nama_barang;
            $barang->stok = $request->stok;

            if ($request->hasFile('gambar')) {
                if ($barang->gambar && Storage::exists('public/barangs/' . $barang->gambar)) {
                    Storage::delete('public/barangs/' . $barang->gambar);
                }

                $path = $request->file('gambar')->store('public/barangs');
                $barang->gambar = basename($path);
            }

            $barang->save();

            $perubahanStok = $barang->stok - $stokLama;
            if ($perubahanStok > 0) {
                $keterangan = 'Edit Tambah Stok';
            } elseif ($perubahanStok < 0) {
                $keterangan = 'Edit Kurangi Stok';
            } else {
                $keterangan = '-';
            }

            if ($perubahanStok != 0) {
                RiwayatPenambahan::create([
                    'barang_id' => $barang->id,
                    'jumlah' => abs($perubahanStok),
                    'keterangan' => $keterangan,
                ]);
            }

            alert()->success('Sukses', 'Barang berhasil diupdate.');
        } catch (\Exception $e) {
            alert()->error('Error', 'Terjadi kesalahan saat mengupdate barang.');
        }

        return redirect()->route('barang.index');
    }
    public function riwayatPenambahan(Request $request)
    {
        $riwayats = RiwayatPenambahan::with('barang')->orderBy('created_at', 'desc')->paginate(10);

        return view('riwayat-penambahan', compact('riwayats'));
    }

    public function destroy(Barang $barang)
    {
        try {
            if ($barang->gambar && Storage::exists('public/barangs/' . $barang->gambar)) {
                Storage::delete('public/barangs/' . $barang->gambar);
            }

            $barang->delete();
            alert()->success('Sukses', 'Barang berhasil dihapus.');
        } catch (\Exception $e) {
            alert()->error('Error', 'Terjadi kesalahan saat menghapus barang.');
        }

        return redirect()->route('barang.index');
    }

    public function updateStock(Request $request, Barang $barang)
    {
        try {
            $request->validate([
                'stok' => 'required|integer|min:1',
            ]);

            $barang->stok -= $request->stok;
            $barang->save();

            RiwayatPengambilan::create([
                'barang_id' => $barang->id,
                'jumlah' => $request->stok,
            ]);

            alert()->success('Sukses', 'Barang berhasil diambil.');
        } catch (\Exception $e) {
            alert()->error('Error', 'Terjadi kesalahan saat mengambil barang.');
        }

        return redirect()->route('dashboard');
    }

    public function hapusTerpilihRiwayat(Request $request)
    {
        try {
            $selectedItems = $request->input('items', []);

            if (empty($selectedItems)) {
                alert()->info('Info', 'Tidak ada riwayat yang dipilih untuk dihapus.');
                return redirect()->back();
            }

            RiwayatPengambilan::whereIn('id', json_decode($selectedItems))->delete();
            alert()->success('Sukses', 'Riwayat terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            alert()->error('Error', 'Terjadi kesalahan saat menghapus riwayat.');
        }

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $barangs = Barang::where('nama_barang', 'like', "%{$query}%")->get();

        return view('dashboard', compact('barangs'));
    }

    public function cari(Request $request)
    {
        $query = $request->input('query');
        $barangs = Barang::where('nama_barang', 'like', "%{$query}%")->get();

        return view('manage', compact('barangs'));
    }
    public function riwayat(Request $request)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        // Mengambil riwayat pengambilan berdasarkan bulan dan tahun
        $riwayats = RiwayatPengambilan::with('barang')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();
        $barangs = Barang::all();

        return view('riwayat', compact('riwayats', 'barangs', 'month', 'year'));
    }

    public function exportRiwayat()
    {
        $riwayat = RiwayatPengambilan::with('barang')->get();

        $barangs = Barang::all();

        foreach ($barangs as $barang) {
            $stokAwal = $barang->stok + RiwayatPengambilan::where('barang_id', $barang->id)->sum('jumlah');
            $stokKeluar = RiwayatPengambilan::where('barang_id', $barang->id)->sum('jumlah');

            $barang->stok_awal = $stokAwal;
            $barang->stok_keluar = $stokKeluar;
        }

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = new Dompdf($options);

        $html = view('pdf', compact('riwayat', 'barangs'))->render();
        $pdf->loadHtml($html);
        $pdf->setPaper('F4', 'portrait');
        $pdf->render();

        $currentMonth = date('F_Y');
        return $pdf->stream("riwayat_pengambilan_{$currentMonth}.pdf");
    }
}
