@foreach ($barangs as $barang)
<div class="col">
    <div class="card h-100 shadow border-0 rounded-3 overflow-hidden">
        <img src="{{ $barang->gambar ? asset('storage/barangs/'.$barang->gambar) : asset('assets/images/default.png') }}"
            class="card-img-top img-fluid" alt="{{ $barang->nama_barang }}" style="height: 200px; object-fit: cover;">

        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-primary text-truncate fw-bold">{{ $barang->nama_barang }}</h5>
            <p class="card-text mb-1">Stok: <span class="fw-bold text-success">{{ $barang->stok }}</span></p>

            @if ($barang->stok > 0)
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="checkbox{{ $barang->id }}"
                    onchange="handleCheckboxChange(event)">
                <label class="form-check-label" for="checkbox{{ $barang->id }}">Pilih</label>
            </div>
            @else
            <button class="btn btn-secondary w-100 mt-2" disabled>Stok Habis</button>
            @endif
        </div>
    </div>
</div>
<script>
let checkboxStatus = {};

document.getElementById('searchInput').addEventListener('keyup', function() {
    const query = this.value;

    // AJAX Request
    fetch("{{ route('barang.search') }}?query=" + query, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const productList = document.getElementById('productList');
            productList.innerHTML = html;

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
        if (checkbox) {
            checkbox.checked = checkboxStatus[id];
        }
    }
}

function handleCheckboxChange(event) {
    const checkbox = event.target;
    toggleAmbilBarangButton();
    toggleCheckbox(checkbox.id, checkbox.checked);
}

function toggleAmbilBarangButton() {
    const checkboxes = document.querySelectorAll('[id^="checkbox"]');
    const ambilBarangBtn = document.getElementById('ambilBarangBtn');
    ambilBarangBtn.disabled = !Array.from(checkboxes).some(checkbox => checkbox.checked);
}

document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('[id^="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkboxStatus[checkbox.id] = checkbox.checked;
        checkbox.addEventListener('change', handleCheckboxChange);
    });
});
</script>

@endforeach