<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengambilan Barang</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        padding: 0;
    }

    h2,
    h3 {
        text-align: center;
        color: #333;
    }

    .info-section {
        margin-bottom: 30px;
        border: 1px solid #ddd;
        padding: 15px;
        background-color: #f9f9f9;
    }

    .info-section h2 {
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #333;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }

    th {
        background-color: #f2f2f2;
    }

    .summary-table {
        width: 100%;
        margin-bottom: 20px;
    }

    .summary-table th,
    .summary-table td {
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .summary-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    </style>
</head>

<body>

    <h2>Laporan Stok & Riwayat Pengambilan</h2>
    <div class="info-section">
        <h3>Ringkasan Stok Barang</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Stok Awal</th>
                    <th>Stok Keluar</th>
                    <th>Stok Akhir</th>
                </tr>
            </thead>
            <tbody>
                @php
                $totalStokAwal = 0;
                $totalStokKeluar = 0;
                $totalStokAkhir = 0;
                @endphp
                @foreach ($barangs as $index => $barang)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->stok_awal }}</td>
                    <td>{{ $barang->stok_keluar }}</td>
                    <td>{{ $barang->stok }}</td>
                </tr>
                @php
                $totalStokAwal += $barang->stok_awal;
                $totalStokKeluar += $barang->stok_keluar;
                $totalStokAkhir += $barang->stok;
                @endphp
                @endforeach
                <tr class="total-row">
                    <th colspan="2">Total Keseluruhan</th>
                    <td>{{ $totalStokAwal }}</td>
                    <td>{{ $totalStokKeluar }}</td>
                    <td>{{ $totalStokAkhir }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <h3>Riwayat Pengambilan Barang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Nama Penerima</th>
                <th>Jenis</th>
                <th>keterangan</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($riwayat as $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->nama_penerima }}</td>
                <td>{{ $item->jenis_pengeluaran }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->created_at->timezone('Asia/Jakarta')->format('d-m-Y - H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
