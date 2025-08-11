@extends('layout.app')
@section('title', 'Dashboard')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    @if (isset($lowStockItems) && $lowStockItems->count() > 0)
        <div id="lowStockNotification" class="position-fixed animate__animated animate__slideInRight"
            style="top: 20px; right: 20px; z-index: 1050; width: 350px;">
            <div class="card border-0" style="background: linear-gradient(135deg, #ff6b6b, #ee5a52);">
                <div
                    class="card-header bg-transparent border-0 text-white d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <div class="me-2" style="font-size: 1.5rem;">⚠️</div>
                        <h6 class="mb-0 fw-bold text-white">Peringatan Stok Rendah</h6>
                    </div>
                    <button type="button" class="btn-close btn-close-white" onclick="closeNotification()"></button>
                </div>

                <div class="card-body text-white pt-0">
                    <p class="mb-3 opacity-90" style="font-size: 0.9rem;">
                        <span class="badge bg-white text-danger fw-bold">{{ $lowStockItems->count() }} item</span>
                        memiliki stok kurang dari 5
                    </p>

                    <div class="low-stock-items" style="max-height: 200px; overflow-y: auto;">
                        @foreach ($lowStockItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 rounded"
                                style="background: rgba(255,255,255,0.1); backdrop-filter: blur(5px);">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold" style="font-size: 0.9rem;">{{ $item->nama_barang }}</div>
                                    <small class="opacity-75">Sisa stok: {{ $item->stok }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-white text-danger fw-bold">{{ $item->stok }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <audio id="warningSound" preload="auto">
            <source src="{{ asset('sounds/warning.mp3') }}" type="audio/mpeg">
        </audio>
    @endif

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2 class="fw-bold text-primary">Produk</h2>
                <input type="text" id="searchInput" class="form-control me-2 w-50" placeholder="Cari produk...">
            </div>
        </div>

        <button id="ambilBarangBtn" class="btn btn-primary mb-3 sticky-top" data-bs-toggle="modal"
            data-bs-target="#modalAmbilBarang" disabled>
            Ambil Barang
        </button>




        <div id="productList" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach ($barangs as $barang)
                <div class="col">
                    <div
                        class="card h-100 shadow border-0 rounded-3 overflow-hidden {{ $barang->stok < 4 ? 'border-danger' : '' }}">
                        <img src="{{ $barang->gambar ? asset('storage/barangs/' . $barang->gambar) : asset('assets/images/default.png') }}"
                            class="card-img-top img-fluid" alt="{{ $barang->nama_barang }}"
                            style="height: 200px; object-fit: cover;">

                        @if ($barang->stok < 4 && $barang->stok > 0)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning text-dark">⚠️ Stok Rendah</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary text-truncate fw-bold">{{ $barang->nama_barang }}</h5>
                            <p class="card-text mb-1">Stok:
                                <span class="fw-bold {{ $barang->stok < 4 ? 'text-danger' : 'text-success' }}">
                                    {{ $barang->stok }}
                                </span>
                            </p>

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
                            <div id="barangList"></div>
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
        let checkboxStatus = {};

        document.getElementById('searchInput').addEventListener('keyup', function() {
            const query = this.value;
            fetch("{{ route('barang.search') }}?query=" + query, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('productList').innerHTML = html;
                    restoreCheckboxState();
                })
                .catch(error => console.error('Error:', error));
        });

        function toggleCheckbox(id, checked) {
            checkboxStatus[id] = checked;
        }

        function restoreCheckboxState() {
            for (const id in checkboxStatus) {
                const checkbox = document.getElementById(id);
                if (checkbox) checkbox.checked = checkboxStatus[id];
            }
        }

        function handleCheckboxChange(event) {
            toggleCheckbox(event.target.id, event.target.checked);
            toggleAmbilBarangButton();
        }

        function toggleAmbilBarangButton() {
            const checkboxes = document.querySelectorAll('[id^="checkbox"]');
            const ambilBarangBtn = document.getElementById('ambilBarangBtn');
            ambilBarangBtn.disabled = !Array.from(checkboxes).some(checkbox => checkbox.checked);
        }

        document.getElementById('ambilBarangBtn').addEventListener('click', function() {
            const barangListContainer = document.getElementById('barangList');
            barangListContainer.innerHTML = '';
            document.querySelectorAll('[id^="checkbox"]').forEach(checkbox => {
                if (checkbox.checked) {
                    const barangId = checkbox.id.replace('checkbox', '');
                    const barangName = checkbox.closest('.card').querySelector('.card-title').innerText;
                    const div = document.createElement('div');
                    div.innerHTML = `
                    <p>Nama Barang: ${barangName}</p>
                    <div class="mb-3">
                        <label for="jumlah${barangId}" class="form-label">Jumlah Pengambilan</label>
                        <input type="number" name="jumlah[${barangId}]" class="form-control" min="1" max="10" value="1" id="jumlah${barangId}" required>
                    </div><hr>`;
                    barangListContainer.appendChild(div);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[id^="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', handleCheckboxChange);
                checkboxStatus[checkbox.id] = checkbox.checked;
            });
        });

        // notifikasi stok rendah
        @if (isset($lowStockItems) && $lowStockItems->count() > 0)

            function closeNotification() {
                const notification = document.getElementById('lowStockNotification');
                if (notification) {
                    notification.classList.add('animate__fadeOutRight');
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 500);
                }
            }
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            // Play warning sound when notification appears
            const audio = document.getElementById('warningSound');
            if (audio) {
                audio.volume = 0.7;
                audio.play().catch(e => console.log('Could not play sound'));
            }
        });

        function closeNotification() {
            document.getElementById('lowStockNotification').remove();
        }
    </script>
@endsection
