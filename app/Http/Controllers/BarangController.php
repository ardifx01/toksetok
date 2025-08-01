<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\RiwayatPenambahan;
use App\Models\RiwayatPengambilan;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('dashboard', compact('barangs'));
    }
    public function panduan()
    {
        return view('panduan');
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
                'stok' => 'required|integer|min:0', // Ubah min:1 menjadi min:0
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

    public function updateStock(Request $request, Barang $barang)
    {
        try {
            $request->validate([
                'jumlah' => 'required|integer|min:1|max:' . $barang->stok,
                'nama_penerima' => 'required|array',
                'nama_penerima.*' => 'required|string|max:255',
                'jenis_pengeluaran' => 'required|string',
                'keterangan' => 'nullable|string',
            ]);

            $totalJumlah = $request->input('jumlah', 1);

            if ($totalJumlah > $barang->stok) {
                throw new \Exception('Jumlah pengambilan melebihi stok yang tersedia');
            }

            $barang->stok -= $totalJumlah;
            $barang->save();

            $namaPenerimaArray = $request->input('nama_penerima');
            $namaPenerimaCount = array_count_values($namaPenerimaArray);

            foreach ($namaPenerimaCount as $nama => $jumlah) {
                RiwayatPengambilan::create([
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah,
                    'nama_penerima' => $nama,
                    'jenis_pengeluaran' => $request->input('jenis_pengeluaran'),
                    'keterangan' => $request->input('keterangan'),
                    'created_at' => now(),
                ]);
            }

            alert()->success('Sukses', 'Barang berhasil diambil.');
        } catch (\Exception $e) {
            alert()->error('Error', $e->getMessage());
            return redirect()->back();
        }

        return redirect()->route('dashboard');
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
    public function hapusTerpilihRiwayat(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*' => 'integer|exists:riwayat_pengambilans,id'
            ]);

            DB::transaction(function () use ($validated) {
                $deletedCount = RiwayatPengambilan::whereIn('id', $validated['items'])->delete();

                if ($deletedCount === 0) {
                    throw new \Exception('Tidak ada riwayat yang berhasil dihapus.');
                }
            });

            $redirectParams = $request->only(['month', 'year']);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus riwayat terpilih.',
                'count' => count($validated['items']),
                'redirect_url' => route('riwayat.index', $redirectParams)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapusTerpilihPenambahan(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*' => 'integer|exists:riwayat_penambahans,id'
            ]);

            DB::transaction(function () use ($validated) {
                $deletedCount = RiwayatPenambahan::whereIn('id', $validated['items'])->delete();

                if ($deletedCount === 0) {
                    throw new \Exception('Tidak ada riwayat yang berhasil dihapus.');
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus riwayat terpilih.',
                'count' => count($validated['items'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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

        $riwayats = RiwayatPengambilan::with('barang')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $barangs = Barang::all();

        return view('riwayat', compact('riwayats', 'barangs', 'month', 'year'));
    }

    public function exportRiwayat(Request $request)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));
        $riwayat = RiwayatPengambilan::with('barang')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

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

        $html = view('pdf', compact('riwayat', 'barangs', 'month', 'year'))->render();
        $pdf->loadHtml($html);
        $pdf->setPaper('F4', 'portrait');
        $pdf->render();

        $currentMonthYear = \DateTime::createFromFormat('!m', $month)->format('F') . "_{$year}";
        return $pdf->stream("riwayat_pengambilan_{$currentMonthYear}.pdf");
    }

    public function updateStockMulti(Request $request)
    {
        try {
            $request->validate([
                'jumlah' => 'required|array',
                'jumlah.*' => 'required|integer|min:1',
                'nama_penerima' => 'required|string|max:255',
                'keterangan' => 'nullable|string',
            ]);

            $namaPenerima = $request->input('nama_penerima');

            foreach ($request->input('jumlah') as $barangId => $jumlah) {
                $barang = Barang::findOrFail($barangId);
                if ($barang->stok < $jumlah) {
                    alert()->error('Error', 'Stok tidak cukup untuk barang: ' . $barang->nama_barang);
                    return redirect()->back();
                }

                $barang->stok -= $jumlah;
                $barang->save();

                RiwayatPengambilan::create([
                    'barang_id' => $barangId,
                    'jumlah' => $jumlah,
                    'nama_penerima' => $namaPenerima,
                    'jenis_pengeluaran' => $request->input('jenis_pengeluaran'),
                    'keterangan' => $request->input('keterangan'),
                    'created_at' => now(),
                ]);
            }

            alert()->success('Sukses', 'Barang berhasil diambil.');
        } catch (\Exception $e) {
            Log::error('Error updating stock: ' . $e->getMessage());

            alert()->error('Error', 'Terjadi kesalahan saat mengambil barang.');
        }

        return redirect()->route('dashboard');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->input('query');
            $barangs = Barang::where('nama_barang', 'like', "%{$query}%")->get();
            return view('partials._barangList', compact('barangs'))->render();
        }
    }
}
