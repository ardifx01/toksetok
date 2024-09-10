<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\RiwayatPengambilan;
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
        // Mendapatkan parameter sort dari request
        $sort = $request->query('sort');

        // Mengurutkan data berdasarkan parameter sort
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

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:1',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

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

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->gambar && Storage::exists('public/barangs/' . $barang->gambar)) {
            Storage::delete('public/barangs/' . $barang->gambar);
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function updateStock(Request $request, Barang $barang)
    {
        $request->validate([
            'stok' => 'required|integer|min:1',
        ]);

        $barang->stok -= $request->stok;
        $barang->save();

        RiwayatPengambilan::create([
            'barang_id' => $barang->id,
            'jumlah' => $request->stok,
        ]);

        return redirect()->route('dashboard')->with('success', 'Barang berhasil diambil.');
    }

    public function riwayat(Request $request)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        // Mengambil riwayat pengambilan berdasarkan bulan dan tahun
        $riwayats = RiwayatPengambilan::with('barang')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get(); // Mengambil semua data tanpa pagination

        return view('riwayat', compact('riwayats', 'month', 'year'));
    }


    public function hapusTerpilihRiwayat(Request $request)
    {
        $selectedItems = $request->input('items', []);

        if (empty($selectedItems)) {
            return redirect()->back()->with('success', 'Tidak ada riwayat yang dipilih untuk dihapus.');
        }

        RiwayatPengambilan::whereIn('id', json_decode($selectedItems))->delete();

        return redirect()->back()->with('success', 'Riwayat terpilih berhasil dihapus.');
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

    public function exportRiwayat()
    {
        $riwayat = RiwayatPengambilan::with('barang')->get();

        $pdf = new Dompdf(new Options(['defaultFont' => 'Arial']));
        $pdf->loadHtml(view('pdf', compact('riwayat'))->render());
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return $pdf->stream('riwayat_pengambilan.pdf');
    }

    public function tambahKeKeranjang(Request $request, Barang $barang)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $barang->stok,
        ]);

        $itemKeranjang = Keranjang::where('barang_id', $barang->id)->first();
        if ($itemKeranjang) {
            // Tambah jumlah jika barang sudah ada di keranjang
            $itemKeranjang->jumlah += $request->jumlah;
            $itemKeranjang->save();
        } else {
            // Masukkan barang baru ke keranjang
            Keranjang::create([
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah,
            ]);
        }

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang.');
    }

    public function lihatKeranjang()
    {
        $keranjangs = Keranjang::with('barang')->get();
        return view('keranjang', compact('keranjangs'));
    }

    public function updateKeranjang(Request $request, Keranjang $keranjang)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1|max:' . $keranjang->barang->stok,
        ]);

        $keranjang->update([
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->back()->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    public function hapusKeranjang(Keranjang $keranjang)
    {
        $keranjang->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        // Tambahkan debugging untuk memeriksa input
        dd($request->input('items'));
        $selectedItems = $request->input('items', []);

        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Tidak ada barang yang dipilih untuk checkout.');
        }

        $keranjangs = Keranjang::whereIn('id', $selectedItems)->get();

        foreach ($keranjangs as $keranjang) {
            $barang = $keranjang->barang;
            if ($barang->stok >= $keranjang->jumlah) {
                $barang->stok -= $keranjang->jumlah;
                $barang->save();

                RiwayatPengambilan::create([
                    'barang_id' => $barang->id,
                    'jumlah' => $keranjang->jumlah,
                ]);
            } else {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk barang: ' . $barang->nama_barang);
            }
        }

        Keranjang::whereIn('id', $selectedItems)->delete();

        return redirect()->route('dashboard')->with('success', 'Checkout berhasil, barang berhasil diambil.');
    }

    public function hapusTerpilih(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);

        if (empty($selectedItems)) {
            return redirect()->back()->with('success', 'Tidak ada barang yang dipilih untuk dihapus.');
        }

        // Hapus barang yang dipilih dari keranjang
        Keranjang::whereIn('id', $selectedItems)->delete();

        return redirect()->back()->with('success', 'Barang terpilih berhasil dihapus dari keranjang.');
    }
}
