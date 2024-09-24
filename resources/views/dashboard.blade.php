@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <!-- Header dengan Form Pencarian di sebelah kanan -->
    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-between align-items-center" style="margin-top: -1rem;">
            <h2 class="fw-bold text-primary">Produk</h2>
            <form action="{{ route('barang.search') }}" method="GET" class="d-flex w-50 shadow-sm">
                <input type="text" name="query" class="form-control me-2" placeholder="Cari produk..."
                    value="{{ request()->query('query') }}" style="border-radius: 0.375rem;">
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
                <img src="{{ asset('storage/barangs/'.$barang->gambar) }}" class="card-img-top img-fluid"
                    alt="{{ $barang->nama_barang }}" style="height: 200px; object-fit: cover;">
                @else
                <img src="{{ asset('assets/images/default.png') }}" class="card-img-top img-fluid" alt="No Image"
                    style="height: 200px; object-fit: cover;">
                @endif

                <div class="card-body d-flex flex-column">
                    <!-- Nama dan Stok Produk -->
                    <h5 class="card-title text-primary text-truncate fw-bold">{{ $barang->nama_barang }}</h5>
                    <p class="card-text mb-1">Stok: <span class="fw-bold text-success">{{ $barang->stok }}</span></p>

                    <!-- Jika Stok Tersedia -->
                    @if ($barang->stok > 0)
                    <div class="mt-auto">
                        <!-- Tombol untuk Memicu Modal -->
                        <button class="btn btn-primary w-100" data-bs-toggle="modal"
                            data-bs-target="#modalAmbilBarang{{ $barang->id }}">
                            Ambil
                        </button>
                    </div>
                    @else
                    <!-- Jika Stok Habis -->
                    <button class="btn btn-secondary w-100 mt-2" disabled>Stok Habis</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Modal Ambil Barang -->
        <div class="modal fade" id="modalAmbilBarang{{ $barang->id }}" tabindex="-1"
            aria-labelledby="modalAmbilBarangLabel{{ $barang->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAmbilBarangLabel{{ $barang->id }}">Ambil Barang:
                            {{ $barang->nama_barang }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('barang.updateStock', $barang->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                            <!-- Input Jumlah -->
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah Pengambilan</label>
                                <input type="number" name="jumlah" class="form-control" value="1" min="1"
                                    max="{{ $barang->stok }}" id="jumlah{{ $barang->id }}" required
                                    onchange="generateNamaPenerimaFields({{ $barang->id }}, this.value)">
                            </div>
                            <!-- Input Jenis Pengeluaran -->
                            <div class="mb-3">
                                <label for="jenis_pengeluaran" class="form-label">Jenis Pengeluaran</label>
                                <select name="jenis_pengeluaran" class="form-select" required>
                                    <option value="Debitur">Debitur</option>
                                    <option value="Nasabah">Nasabah</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <!-- Input Nama Penerima Dinamis -->
                            <div id="namaPenerimaContainer{{ $barang->id }}">
                                <div class="mb-3">
                                    <label for="nama_penerima" class="form-label">Nama Penerima</label>
                                    <input type="text" name="nama_penerima[]" class="form-control" required>
                                </div>
                            </div>
                            <!-- Input Keterangan -->
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Konfirmasi Pengambilan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @include('sweetalert::alert')
</div>

<!-- JavaScript untuk Menambahkan Input Nama Dinamis -->
<script>
function generateNamaPenerimaFields(id, jumlah) {
    const container = document.getElementById('namaPenerimaContainer' + id);
    container.innerHTML = ''; // Hapus input sebelumnya

    for (let i = 0; i < jumlah; i++) {
        const div = document.createElement('div');
        div.classList.add('mb-3');

        const label = document.createElement('label');
        label.classList.add('form-label');
        label.innerText = `Nama Penerima ${i + 1}`;

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'nama_penerima[]';
        input.classList.add('form-control');
        input.required = true;

        div.appendChild(label);
        div.appendChild(input);
        container.appendChild(div);
    }
}
</script>
@endsection