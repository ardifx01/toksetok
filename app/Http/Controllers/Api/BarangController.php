<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\RiwayatPenambahan;
use App\Models\RiwayatPengambilan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    // List all barang
    public function index()
    {
        $barangs = Barang::latest()->get();
        return response()->json([
            'data' => $barangs,
            'message' => 'List of Barang',
        ], 200);
    }

        // Store a new barang
        public function store(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'nama_barang' => 'required|string|max:255',
                'stok' => 'required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            try {
                $barang = new Barang();
                $barang->nama_barang = $request->nama_barang;
                $barang->stok = $request->stok;

                if ($request->hasFile('gambar')) {
                    $path = $request->file('gambar')->store('public/barangs');
                    $barang->gambar = basename($path);
                }

                $barang->save();

                // Mencatat riwayat penambahan stok
                RiwayatPenambahan::create([
                    'barang_id' => $barang->id,
                    'jumlah' => $barang->stok,
                    'keterangan' => 'Penambahan stok baru',
                ]);

                return response()->json([
                    'message' => 'Barang successfully added and logged in RiwayatPenambahan',
                    'data' => $barang,
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Error occurred while adding barang',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        // Update barang
        public function update(Request $request, $id)
        {
            $barang = Barang::find($id);
            if (!$barang) {
                return response()->json(['message' => 'Barang not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'nama_barang' => 'sometimes|required|string|max:255',
                'stok' => 'sometimes|required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 400);
            }

            $barang->update($request->all());

            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('public/barangs');
                $barang->gambar = basename($path);
                $barang->save();
            }

            return response()->json([
                'message' => 'Barang updated successfully',
                'data' => $barang,
            ], 200);
        }

    // Delete barang
    public function destroy($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang not found'], 404);
        }

        // Hapus gambar dari storage jika gambar ada dan bukan gambar default
        if ($barang->gambar && $barang->gambar !== 'default.png') {
            $gambarPath = storage_path('app/public/barangs/' . $barang->gambar);
            if (file_exists($gambarPath)) {
                unlink($gambarPath); // Menghapus file gambar dari local storage
            }
        }

        // Hapus data barang dari database
        $barang->delete();

        return response()->json([
            'message' => 'Barang deleted successfully',
        ], 200);
    }

    // Take item (pengambilan barang)
    public function take(Request $request, $id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['message' => 'Barang not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'stok' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 400);
        }

        if ($barang->stok < $request->stok) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        $barang->stok -= $request->stok;
        $barang->save();

        RiwayatPengambilan::create([
            'barang_id' => $barang->id,
            'jumlah' => $request->stok,
        ]);

        return response()->json([
            'message' => 'Barang successfully taken',
            'data' => $barang,
        ], 200);
    }

    // Search barang by name
    public function search(Request $request)
    {
        $query = $request->input('query');
        $barangs = Barang::where('nama_barang', 'like', "%{$query}%")->get();

        return response()->json([
            'message' => 'Search results',
            'data' => $barangs,
        ], 200);
    }

    // List all riwayat pengambilan
    public function riwayatPengambilan(Request $request)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $riwayats = RiwayatPengambilan::with('barang')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        return response()->json([
            'message' => 'Riwayat Pengambilan',
            'data' => $riwayats,
        ], 200);
    }
}