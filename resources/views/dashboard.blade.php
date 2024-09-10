@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <!-- Header dengan Form Pencarian di sebelah kanan -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold">Produk</h2>
            <form action="{{ route('barang.search') }}" method="GET" class="d-flex w-50">
                <input type="text" name="query" class="form-control me-2" placeholder="Cari produk..." value="{{ request()->query('query') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>
    </div>

    <!-- Grid Layout untuk Menampilkan Produk -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($barangs as $barang)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <!-- Gambar Produk -->
                @if ($barang->gambar)
                <img src="{{ asset('storage/barangs/'.$barang->gambar) }}" class="card-img-top img-fluid object-fit-cover" alt="{{ $barang->nama_barang }}" style="height: 200px; object-fit: cover;">
                @else
                <img src="{{ asset('assets/images/default.png') }}" class="card-img-top img-fluid object-fit-cover" alt="No Image" style="height: 200px; object-fit: cover;">
                @endif

                <div class="card-body d-flex flex-column">
                    <!-- Nama dan Stok Produk -->
                    <h5 class="card-title text-truncate">{{ $barang->nama_barang }}</h5>
                    <p class="card-text mb-2">Stok: <span class="fw-bold">{{ $barang->stok }}</span></p>

                    <!-- Jika Stok Tersedia -->
                    @if ($barang->stok > 0)
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Form Ambil Barang -->
                            <form action="{{ route('barang.updateStock', $barang->id) }}" method="POST" class="me-2 flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <input type="number" name="stok" value="1" class="form-control" min="1" max="{{ $barang->stok }}">
                                    <button type="submit" class="btn btn-primary">Ambil</button>
                                </div>
                            </form>

                            <!-- Form Tambah ke Keranjang -->
                            <form action="{{ route('keranjang.tambah', $barang->id) }}" method="POST" class="ms-2">
                                @csrf
                                <input type="hidden" name="jumlah" value="1">
                                <button type="submit" class="btn btn-success" style="white-space: nowrap;">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <!-- Jika Stok Habis -->
                    <button class="btn btn-secondary w-100 mt-2" disabled>Stok Habis</button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
