@extends('layout.app')
@section('title', 'Riwayat Pengambilan')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('riwayat.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-primary">
                <i class="far fa-file-pdf"></i> PDF
            </a>

            <button type="button" id="deleteSelectedBtn" class="btn btn-danger" style="display: none;"
                onclick="document.getElementById('deleteForm').submit();"><i class="far fa-trash-alt"></i> Hapus</button>
        </div>

        <form action="{{ route('riwayat.hapusTerpilih') }}" method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <input type="hidden" name="items" id="deleteItems">
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
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $riwayats->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @include('sweetalert::alert')
    </div>

    <script>
        document.getElementById('selectAllCheckbox').addEventListener('change', function () {
            let isChecked = this.checked;
            document.querySelectorAll('.item-checkbox').forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
            toggleDeleteButton();
        });

        document.querySelectorAll('.item-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                toggleDeleteButton();
            });
        });

        function toggleDeleteButton() {
            let selectedItems = Array.from(document.querySelectorAll('input.item-checkbox:checked'));
            document.getElementById('deleteSelectedBtn').style.display = selectedItems.length > 0 ? 'inline-block' : 'none';
        }

        document.querySelector('button.btn-danger').addEventListener('click', function () {
            let selectedItems = Array.from(document.querySelectorAll('input.item-checkbox:checked')).map(cb => cb
                .value);
            document.getElementById('deleteItems').value = JSON.stringify(selectedItems);
            document.getElementById('deleteForm').submit();
        });
    </script>
@endsection
