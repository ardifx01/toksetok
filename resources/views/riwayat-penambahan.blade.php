@extends('layout.app')
@section('title', 'Riwayat Penambahan')
@section('content')
    <div class="container mt-5">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <form id="delete-form">
            <div class="d-flex justify-content-between mb-4">
                <button id="delete-button" type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal" disabled>Hapus Terpilih</button>
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

            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah Anda yakin ingin menghapus riwayat yang dipilih?
                            <div id="delete-error" class="alert alert-danger mt-2 d-none"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="button" id="confirm-delete" class="btn btn-danger">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Fungsi untuk toggle checkbox
            document.getElementById('select-all').addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('input[name="items[]"]');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                toggleDeleteButton();
            });

            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    toggleDeleteButton();
                    updateSelectAllCheckbox();
                });
            });

            function toggleDeleteButton() {
                const selectedItems = document.querySelectorAll('input[name="items[]"]:checked');
                const deleteButton = document.getElementById('delete-button');
                deleteButton.disabled = selectedItems.length === 0;
            }

            function updateSelectAllCheckbox() {
                const checkboxes = document.querySelectorAll('input[name="items[]"]');
                const selectAll = document.getElementById('select-all');
                const checkedCount = document.querySelectorAll('input[name="items[]"]:checked').length;

                selectAll.checked = checkedCount === checkboxes.length;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            }

            document.getElementById('confirm-delete').addEventListener('click', async function () {
                const selectedItems = Array.from(document.querySelectorAll('input[name="items[]"]:checked'))
                    .map(checkbox => checkbox.value);

                if (selectedItems.length === 0) {
                    showError('Pilih minimal satu riwayat yang ingin dihapus.');
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    formData.append('_method', 'DELETE');
                    selectedItems.forEach(item => formData.append('items[]', item));

                    const response = await fetch("{{ route('riwayat.hapusTerpilihPenambahan') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        let errorMessage = data.message || 'Terjadi kesalahan saat menghapus riwayat';
                        if (data.errors) {
                            errorMessage = Object.values(data.errors).join('<br>');
                        }
                        throw new Error(errorMessage);
                    }

                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
                        modal.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Gagal menghapus riwayat');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showError(error.message);
                }
            });

            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: message,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
@endsection
