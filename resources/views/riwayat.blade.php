@extends('layout.app')
@section('title', 'Riwayat Pengambilan')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('riwayat.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-primary">
                <i class="far fa-file-pdf"></i> PDF
            </a>

            <button type="button" id="deleteSelectedBtn" class="btn btn-danger" style="display: none;">
                <i class="far fa-trash-alt"></i> Hapus
            </button>
        </div>

        <form action="{{ route('riwayat.hapusTerpilih') }}" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
        </form>

        <div class="mb-4">
            <form action="{{ route('riwayat.index') }}" method="GET">
                <div class="d-flex align-items-center">
                    <label for="month" class="me-2">Bulan:</label>
                    <select name="month" id="month" class="form-select form-select-sm">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02d', $i) }}" {{ $month == sprintf('%02d', $i) ? 'selected' : '' }}>
                                {{ \DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>

                    <label for="year" class="ms-3 me-2">Tahun:</label>
                    <select name="year" id="year" class="form-select form-select-sm">
                        @for ($i = 2020; $i <= date('Y'); $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>

                    <button type="submit" class="btn btn-primary ms-3">Filter</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>
                            <input class="form-check-input" type="checkbox" id="selectAllCheckbox">
                        </th>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Nama Penerima</th>
                        <th>Jenis Pengeluaran</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayats as $riwayat)
                        <tr>
                            <td>
                                <input class="form-check-input item-checkbox" type="checkbox" value="{{ $riwayat->id }}"
                                    id="riwayat{{ $riwayat->id }}">
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $riwayat->barang->nama_barang }}</td>
                            <td>{{ $riwayat->jumlah }}</td>
                            <td>{{ $riwayat->nama_penerima }}</td>
                            <td>{{ $riwayat->jenis_pengeluaran }}</td>
                            <td>{{ $riwayat->keterangan }}</td>
                            <td>{{ $riwayat->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $riwayats->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @include('sweetalert::alert')
    </div>

    <script>
        function getSelectedItems() {
            return Array.from(document.querySelectorAll('.item-checkbox:checked')).map(checkbox => checkbox.value);
        }

        function toggleDeleteButton() {
            document.getElementById('deleteSelectedBtn').style.display =
                getSelectedItems().length > 0 ? 'block' : 'none';
        }

        document.getElementById('selectAllCheckbox').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleDeleteButton();
        });

        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleDeleteButton);
        });

        document.getElementById('deleteSelectedBtn').addEventListener('click', function (e) {
            e.preventDefault();

            const selectedItems = getSelectedItems();

            if (selectedItems.length === 0) {
                Swal.fire('Error', 'Tidak ada item yang dipilih', 'error');
                return;
            }

            const form = document.createElement('form');
            form.action = "{{ route('riwayat.hapusTerpilih') }}";
            form.method = 'POST';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = "{{ csrf_token() }}";
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            selectedItems.forEach(item => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'items[]';
                input.value = item;
                form.appendChild(input);
            });

            const urlParams = new URLSearchParams(window.location.search);
            const monthInput = document.createElement('input');
            monthInput.type = 'hidden';
            monthInput.name = 'month';
            monthInput.value = urlParams.get('month') || '';
            form.appendChild(monthInput);

            const yearInput = document.createElement('input');
            yearInput.type = 'hidden';
            yearInput.name = 'year';
            yearInput.value = urlParams.get('year') || '';
            form.appendChild(yearInput);

            document.body.appendChild(form);

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: `Anda akan menghapus ${selectedItems.length} item`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = data.redirect_url;
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus', 'error');
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            document.body.removeChild(form);
                        });
                } else {
                    document.body.removeChild(form);
                }
            });
        });
    </script>
@endsection
