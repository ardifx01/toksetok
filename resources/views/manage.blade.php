@extends('layout.app')
@section('title', 'Daftar Barang')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/manage.css') }}">
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <!-- Tombol Tambah Barang dan Form Pencarian -->
                <div class="col-12 d-flex align-items-center justify-content-between mb-3 sticky-top py-3"
                    style="top: 0; z-index: 1020;">
                    <!-- Tombol Tambah Barang -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
                        Tambah Barang
                    </button>

                    <!-- Form Pencarian -->
                    <form action="{{ route('cari') }}" method="GET" class="d-flex ms-3" style="max-width: 350px;">
                        <input type="text" name="query" class="form-control me-2" placeholder="Cari produk..."
                            value="{{ request()->query('query') }}">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>

                <!-- Tabel Barang -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center table-dark-mode">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>
                                    Stok
                                    <a href="{{ route('barang.index', ['sort' => 'stok_asc']) }}"
                                        title="Urutkan stok terkecil">
                                        <i class="fas fa-arrow-down"></i>
                                    </a>
                                    <a href="{{ route('barang.index', ['sort' => 'stok_desc']) }}"
                                        title="Urutkan stok terbesar">
                                        <i class="fas fa-arrow-up"></i>
                                    </a>
                                </th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangs as $index => $barang)
                                <tr class="{{ $barang->stok <= 4 ? 'low-stock-row' : '' }}">
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->stok }}</td>
                                    <td>
                                        @if ($barang->gambar)
                                            <img src="{{ asset('storage/barangs/' . $barang->gambar) }}"
                                                alt="{{ $barang->nama_barang }}" width="100">
                                        @else
                                            <span class="text-muted">Tidak ada gambar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Edit yang memicu modal edit -->
                                        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal"
                                            data-bs-target="#editBarangModal{{ $barang->id }}">
                                            <i class="far fa-edit text-primary fa-lg"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                            class="d-inline ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-link p-0"
                                                onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                                <i class="far fa-trash-alt text-primary fa-lg"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>

                                <!-- Modal Edit Barang -->
                                <div class="modal fade" id="editBarangModal{{ $barang->id }}" tabindex="-1"
                                    aria-labelledby="editBarangModalLabel{{ $barang->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('barang.update', $barang->id) }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editBarangModalLabel{{ $barang->id }}">
                                                        Edit Barang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama_barang{{ $barang->id }}" class="form-label">Nama
                                                            Barang</label>
                                                        <input type="text"
                                                            class="form-control @error('nama_barang') is-invalid @enderror"
                                                            id="nama_barang{{ $barang->id }}" name="nama_barang"
                                                            value="{{ old('nama_barang', $barang->nama_barang) }}"
                                                            required>
                                                        @error('nama_barang')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="stok{{ $barang->id }}"
                                                            class="form-label">Stok</label>
                                                        <input type="number"
                                                            class="form-control @error('stok') is-invalid @enderror"
                                                            id="stok{{ $barang->id }}" name="stok"
                                                            value="{{ old('stok', $barang->stok) }}" required>
                                                        @error('stok')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="gambar{{ $barang->id }}" class="form-label">Gambar
                                                            Barang</label>
                                                        <input type="file"
                                                            class="form-control @error('gambar') is-invalid @enderror"
                                                            id="gambar{{ $barang->id }}" name="gambar">
                                                        @error('gambar')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                        <div class="form-text">Opsional. Kosongkan jika tidak ingin mengubah
                                                            gambar.</div>
                                                    </div>
                                                    @if ($barang->gambar)
                                                        <div class="mb-3">
                                                            <img src="{{ asset('storage/barangs/' . $barang->gambar) }}"
                                                                alt="{{ $barang->nama_barang }}" width="100">
                                                            <div class="form-text">Gambar saat ini.</div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal Edit Barang -->
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('sweetalert::alert')
    </div>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="tambahBarangModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahBarangModalLabel">Tambah Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok"
                                name="stok" value="{{ old('stok') }}" required>
                            @error('stok')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar Barang</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                id="gambar" name="gambar">
                            @error('gambar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="form-text">Opsional</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Barang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
