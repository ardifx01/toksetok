@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <!-- Header dengan Form Pencarian di sebelah kanan -->
    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-between align-items-center" style="margin-top: -1rem;" >
            <h2 class="fw-bold text-primary">Produk</h2>
            <form action="{{ route('barang.search') }}" method="GET" class="d-flex w-50 shadow-sm">
                <input type="text" name="query" class="form-control me-2" placeholder="Cari produk..." value="{{ request()->query('query') }}" style="border-radius: 0.375rem;">
                <button type="submit" class="btn btn-primary" style="border-radius: 0.375rem;">Cari</button>
            </form>
        </div>
    </div>

    <!-- Grid Layout untuk Menampilkan Produk -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($barangs as $barang)
        <div class="col">
            <div class="card h-100 shadow-lg border-0 rounded-3 overflow-hidden">
                <!-- Gambar Produk -->
                @if ($barang->gambar)
                <img src="{{ asset('storage/barangs/'.$barang->gambar) }}" class="card-img-top img-fluid" alt="{{ $barang->nama_barang }}" style="height: 200px; object-fit: cover;">
                @else
                <img src="{{ asset('assets/images/default.png') }}" class="card-img-top img-fluid" alt="No Image" style="height: 200px; object-fit: cover;">
                @endif

                <div class="card-body d-flex flex-column">
                    <!-- Nama dan Stok Produk -->
                    <h5 class="card-title text-primary text-truncate fw-bold">{{ $barang->nama_barang }}</h5>
                    <p class="card-text mb-1">Stok: <span class="fw-bold text-success">{{ $barang->stok }}</span></p>

                    <!-- Jika Stok Tersedia -->
                    @if ($barang->stok > 0)
                    <div class="mt-auto">
                        <form action="{{ route('barang.updateStock', $barang->id) }}" method="POST" class="d-flex">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <input type="number" name="stok" value="1" class="form-control" min="1" max="{{ $barang->stok }}">
                                <button type="submit" class="btn btn-primary" style="border-radius: 0;">Ambil</button>
                            </div>
                        </form>
                    </div>
                    @else
                    <!-- Jika Stok Habis -->
                    <button class="btn btn-secondary w-100 mt-2 " disabled>Stok Habis</button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @include('sweetalert::alert')
</div>
@endsection
