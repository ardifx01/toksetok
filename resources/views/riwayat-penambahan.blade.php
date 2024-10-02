@extends('layout.app')
@section('title', 'Riwayat Penambahan')
@section('content')
<div class="container mt-5">
    <form id="delete-form" method="POST" action="{{ route('riwayat.hapusTerpilih') }}">
        @csrf
        @method('DELETE')

        <div class="d-flex justify-content-between mb-4">
            <button id="delete-button" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" disabled>Hapus Terpilih</button>
        </div>

        <table class="table table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
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
                    <td><input type="checkbox" name="items[]" value="{{ $riwayat->id }}" class="item-checkbox"></td>
                    <td>{{ $loop->iteration + ($riwayats->currentPage() - 1) * $riwayats->perPage() }}</td>
                    <td>{{ $riwayat->barang->nama_barang }}</td>
                    <td>{{ $riwayat->jumlah }}</td>
                    <td>{{ $riwayat->keterangan }}</td>
                    <td>{{ $riwayat->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $riwayats->links('pagination::bootstrap-5') }}
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus riwayat yang dipilih?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input[name="items[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        toggleDeleteButton();
    });

    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleDeleteButton);
    });

    function toggleDeleteButton() {
        const selectedItems = document.querySelectorAll('input[name="items[]"]:checked');
        const deleteButton = document.getElementById('delete-button');
        deleteButton.disabled = selectedItems.length === 0;
    }

    document.getElementById('delete-form').addEventListener('submit', function(event) {
        const selectedItems = document.querySelectorAll('input[name="items[]"]:checked');
        if (selectedItems.length === 0) {
            event.preventDefault();
            alert('Pilih minimal satu riwayat yang ingin dihapus.');
        }
    });
</script>
@endsection
