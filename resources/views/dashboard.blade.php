@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold text-primary">Produk</h2>
            <form action="{{ route('barang.search') }}" method="GET" class="d-flex w-50">
                <input type="text" name="query" class="form-control me-2" placeholder="Cari produk..."
                    value="{{ request()->query('query') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>
    </div>

    <!-- Tombol Ambil Barang -->
    <button id="ambilBarangBtn" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAmbilBarang"
        disabled>Ambil Barang</button>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($barangs as $barang)
        <div class="col">
            <div class="card h-100 shadow border-0 rounded-3 overflow-hidden">
                <img src="{{ $barang->gambar ? asset('storage/barangs/'.$barang->gambar) : asset('assets/images/default.png') }}"
                    class="card-img-top img-fluid" alt="{{ $barang->nama_barang }}"
                    style="height: 200px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary text-truncate fw-bold">{{ $barang->nama_barang }}</h5>
                    <p class="card-text mb-1">Stok: <span class="fw-bold text-success">{{ $barang->stok }}</span></p>

                    @if ($barang->stok > 0)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="checkbox{{ $barang->id }}"
                            onchange="toggleAmbilBarangButton()">
                        <label class="form-check-label" for="checkbox{{ $barang->id }}">Pilih</label>
                    </div>
                    @else
                    <button class="btn btn-secondary w-100 mt-2" disabled>Stok Habis</button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Modal Ambil Barang -->
    <div class="modal fade" id="modalAmbilBarang" tabindex="-1" aria-labelledby="modalAmbilBarangLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAmbilBarangLabel">Ambil Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ambilBarangForm" action="{{ route('barang.updateStockMulti') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="barangList"></div> <!-- Daftar barang yang dipilih -->
                        <div class="mb-3">
                            <label for="nama_penerima" class="form-label">Nama Penerima</label>
                            <input type="text" name="nama_penerima" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_pengeluaran" class="form-label">Jenis Pengeluaran</label>
                            <select name="jenis_pengeluaran" class="form-select" required>
                                <option value="Debitur">Debitur</option>
                                <option value="Nasabah">Nasabah</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
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

    @include('sweetalert::alert')
</div>

<script>
function toggleAmbilBarangButton() {
    const checkboxes = document.querySelectorAll('[id^="checkbox"]');
    const ambilBarangBtn = document.getElementById('ambilBarangBtn');
    ambilBarangBtn.disabled = !Array.from(checkboxes).some(checkbox => checkbox.checked);
}

document.getElementById('ambilBarangBtn').addEventListener('click', function() {
    const barangListContainer = document.getElementById('barangList');
    barangListContainer.innerHTML = ''; // Clear previous selections

    const checkboxes = document.querySelectorAll('[id^="checkbox"]');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const barangId = checkbox.id.replace('checkbox', ''); // Extract barang ID
            const barangName = checkbox.closest('.card').querySelector('.card-title')
            .innerText; // Get the barang name
            const div = document.createElement('div');
            div.innerHTML = `
                <p>Nama Barang: ${barangName}</p>
                <div class="mb-3">
                    <label for="jumlah${barangId}" class="form-label">Jumlah Pengambilan</label>
                    <input type="number" name="jumlah[${barangId}]" class="form-control" min="1" max="10" value="1" id="jumlah${barangId}" required>
                </div>
                <hr>
            `;
            barangListContainer.appendChild(div);
        }
    });
});
</script>
@endsection