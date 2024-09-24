@extends('layout.app')
@section('title', 'Riwayat Penambahan')
@section('content')
<div class="container mt-5">
    <table class="table table-bordered table-hover align-middle text-center">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Jumlah Ditambahkan</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($riwayats as $index => $riwayat)
            <tr>
                <td>{{ $loop->iteration + ($riwayats->currentPage() - 1) * $riwayats->perPage() }}</td>
                <td>{{ $riwayat->barang->nama_barang }}</td>
                <td>{{ $riwayat->jumlah }}</td>
                <td>{{ $riwayat->keterangan }}</td>
                <td>{{ $riwayat->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $riwayats->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection