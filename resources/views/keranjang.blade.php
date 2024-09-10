@extends('layout.app')
@section('title', 'Keranjang')

@section('content')
<div class="container py-5">
    @if (session('success'))
    <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if ($keranjangs->count() > 0)
    <form action="{{ route('keranjang.checkout') }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="submit" class="btn btn-success btn-lg">Checkout</button>
            <button type="button" id="deleteSelectedBtn" class="btn btn-danger btn-lg" style="display: none;" onclick="document.getElementById('deleteForm').submit();">Hapus Barang Terpilih</button>
        </div>

        <div class="row g-4">
            @foreach ($keranjangs as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input item-checkbox" type="checkbox" name="items[]" value="{{ $item->id }}" id="item{{ $item->id }}">
                            <label class="form-check-label" for="item{{ $item->id }}">Pilih untuk checkout</label>
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            @if ($item->barang->gambar)
                            <img src="{{ asset('storage/barangs/'.$item->barang->gambar) }}" alt="{{ $item->barang->nama_barang }}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            @else
                            <img src="{{ asset('assets/images/default.png') }}" alt="No Image" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            @endif
                        </div>
                        <h5 class="card-title text-center mb-3">{{ $item->barang->nama_barang }}</h5>
                        <form action="{{ route('keranjang.update', $item->id) }}" method="POST" class="d-flex justify-content-center align-items-center mb-3">
                            @csrf
                            @method('PATCH')
                            <div class="input-group" style="max-width: 150px;">
                                <input type="number" name="jumlah" value="{{ $item->jumlah }}" class="form-control" min="1" max="{{ $item->barang->stok }}">
                                <button type="submit" class="btn btn-primary">✓</button>
                            </div>
                        </form>
                        <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </form>

    <form action="{{ route('keranjang.hapusTerpilih') }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
        <input type="hidden" name="items" id="deleteItems">
    </form>

    @else
    <div class="alert alert-info text-center">Keranjang Anda kosong.</div>
    @endif
</div>

<script>
    document.querySelectorAll('.item-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            toggleDeleteButton();
        });
    });

    function toggleDeleteButton() {
        let selectedItems = Array.from(document.querySelectorAll('input.item-checkbox:checked'));
        document.getElementById('deleteSelectedBtn').style.display = selectedItems.length > 0 ? 'inline-block' : 'none';
    }

    document.querySelector('button.btn-danger').addEventListener('click', function() {
        let selectedItems = Array.from(document.querySelectorAll('input.item-checkbox:checked')).map(cb => cb.value);
        document.getElementById('deleteItems').value = JSON.stringify(selectedItems);
        document.getElementById('deleteForm').submit();
    });
</script>
@endsection
