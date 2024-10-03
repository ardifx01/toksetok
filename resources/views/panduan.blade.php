@extends('layout.app')
@section('title', 'Panduan Penggunaan')

@section('content')
<div class="container mt-5">
    <h4 class="text-center mb-4">Panduan Penggunaan Dashboard</h4>

    <div class="accordion" id="dashboardAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    1. Melihat Daftar Produk
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body">
                    Untuk melihat daftar produk, buka halaman Dashboard. Anda akan melihat daftar produk dalam bentuk kartu. Setiap kartu menampilkan nama produk, gambar, dan stok yang tersedia.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    2. Mencari Produk
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body">
                    Untuk mencari produk, gunakan kolom pencarian di bagian atas daftar produk. Ketikkan nama produk yang ingin Anda cari. Hasil pencarian akan ditampilkan secara otomatis.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    3. Mengambil Barang
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body">
                    Untuk mengambil barang, centang kotak di samping produk yang ingin Anda ambil, kemudian klik tombol "Ambil Barang". Sebuah modal akan muncul untuk mengonfirmasi pengambilan barang. Isi informasi yang diperlukan dan klik "Konfirmasi Pengambilan".
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    4. Konfirmasi Pengambilan
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body">
                    Setelah Anda memilih barang, modal akan menunjukkan daftar barang yang dipilih. Isi jumlah pengambilan untuk setiap barang dan tambahkan informasi penerima. Klik "Konfirmasi Pengambilan" untuk menyelesaikan proses.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    5. Memeriksa Stok Barang
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body">
                    Untuk memeriksa stok barang, lihat informasi stok yang ditampilkan di bawah nama produk pada setiap kartu. Jika stok habis, tombol "Pilih" tidak akan tersedia.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h4 class="text-center mb-4">Panduan Penggunaan Website</h4>

    <div class="accordion" id="websiteAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOneWeb">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOneWeb" aria-expanded="true" aria-controls="collapseOneWeb">
                    1. Melihat Data Barang
                </button>
            </h2>
            <div id="collapseOneWeb" class="accordion-collapse collapse show" aria-labelledby="headingOneWeb" data-bs-parent="#websiteAccordion">
                <div class="accordion-body">
                    Untuk melihat data barang, klik pada menu "Barang" di sidebar. Di halaman ini, Anda dapat melihat daftar barang beserta stoknya. Anda juga dapat menggunakan fitur pencarian untuk menemukan barang tertentu dengan memasukkan nama barang pada kolom pencarian dan mengklik tombol "Cari".
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwoWeb">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwoWeb" aria-expanded="false" aria-controls="collapseTwoWeb">
                    2. Menambah Barang
                </button>
            </h2>
            <div id="collapseTwoWeb" class="accordion-collapse collapse" aria-labelledby="headingTwoWeb" data-bs-parent="#websiteAccordion">
                <div class="accordion-body">
                    Untuk menambah barang baru, klik tombol "Tambah Barang" pada halaman "Data Barang". Sebuah modal akan muncul. Isi form dengan nama barang, jumlah stok, dan unggah gambar barang jika ada. Setelah semua informasi diisi, klik tombol "Simpan Barang" untuk menyimpan data barang baru.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThreeWeb">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThreeWeb" aria-expanded="false" aria-controls="collapseThreeWeb">
                    3. Mengedit Barang
                </button>
            </h2>
            <div id="collapseThreeWeb" class="accordion-collapse collapse" aria-labelledby="headingThreeWeb" data-bs-parent="#websiteAccordion">
                <div class="accordion-body">
                    Untuk mengedit barang, klik tombol "Edit" di samping barang yang ingin diubah pada halaman "Data Barang". Modal akan terbuka dengan data barang yang ada. Lakukan perubahan yang diperlukan dan klik "Simpan Perubahan" untuk menyimpan update tersebut.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFourWeb">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourWeb" aria-expanded="false" aria-controls="collapseFourWeb">
                    4. Menghapus Barang
                </button>
            </h2>
            <div id="collapseFourWeb" class="accordion-collapse collapse" aria-labelledby="headingFourWeb" data-bs-parent="#websiteAccordion">
                <div class="accordion-body">
                    Untuk menghapus barang, klik tombol "Hapus" di samping barang yang ingin dihapus pada halaman "Data Barang". Anda akan diminta untuk mengonfirmasi penghapusan. Jika Anda yakin, klik "OK" dan barang akan dihapus dari database.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFiveWeb">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFiveWeb" aria-expanded="false" aria-controls="collapseFiveWeb">
                    5. Mengurutkan Data Barang
                </button>
            </h2>
            <div id="collapseFiveWeb" class="accordion-collapse collapse" aria-labelledby="headingFiveWeb" data-bs-parent="#websiteAccordion">
                <div class="accordion-body">
                    Anda dapat mengurutkan data barang berdasarkan stok dengan mengklik ikon panah yang sesuai di header kolom "Stok". Klik ikon panah ke bawah untuk mengurutkan dari stok terkecil ke terbesar, dan klik ikon panah ke atas untuk mengurutkan dari stok terbesar ke terkecil.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h4 class="text-center mb-4">Panduan Penggunaan Halaman Riwayat Pengambilan</h4>

    <div class="accordion" id="riwayatAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOneRiwayat">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOneRiwayat" aria-expanded="true" aria-controls="collapseOneRiwayat">
                    1. Ekspor Riwayat ke PDF
                </button>
            </h2>
            <div id="collapseOneRiwayat" class="accordion-collapse collapse show" aria-labelledby="headingOneRiwayat" data-bs-parent="#riwayatAccordion">
                <div class="accordion-body">
                    Anda dapat mengekspor riwayat pengambilan barang ke dalam format PDF dengan mengklik tombol "Ekspor PDF" di bagian atas tabel riwayat. Sistem akan menghasilkan file PDF yang berisi semua detail riwayat pengambilan barang Anda.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwoRiwayat">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwoRiwayat" aria-expanded="false" aria-controls="collapseTwoRiwayat">
                    2. Mencari Riwayat
                </button>
            </h2>
            <div id="collapseTwoRiwayat" class="accordion-collapse collapse" aria-labelledby="headingTwoRiwayat" data-bs-parent="#riwayatAccordion">
                <div class="accordion-body">
                    Untuk mencari riwayat tertentu, gunakan kolom pencarian di bagian atas tabel riwayat. Ketikkan nama barang atau tanggal yang ingin Anda cari, dan hasil pencarian akan ditampilkan secara otomatis.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThreeRiwayat">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThreeRiwayat" aria-expanded="false" aria-controls="collapseThreeRiwayat">
                    3. Menghapus Riwayat
                </button>
            </h2>
            <div id="collapseThreeRiwayat" class="accordion-collapse collapse" aria-labelledby="headingThreeRiwayat" data-bs-parent="#riwayatAccordion">
                <div class="accordion-body">
                    Anda dapat menghapus riwayat tertentu dengan mengklik tombol "Hapus" di samping entri riwayat yang ingin dihapus. Sebuah modal konfirmasi akan muncul untuk meminta konfirmasi Anda sebelum riwayat tersebut dihapus.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFourRiwayat">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourRiwayat" aria-expanded="false" aria-controls="collapseFourRiwayat">
                    4. Mengurutkan Riwayat
                </button>
            </h2>
            <div id="collapseFourRiwayat" class="accordion-collapse collapse" aria-labelledby="headingFourRiwayat" data-bs-parent="#riwayatAccordion">
                <div class="accordion-body">
                    Anda dapat mengurutkan riwayat pengambilan berdasarkan tanggal atau nama barang dengan mengklik header kolom yang sesuai pada tabel riwayat. Ini memudahkan Anda untuk menemukan informasi yang diinginkan.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h4 class="text-center mb-4">Panduan Penggunaan Halaman Riwayat Penambahan</h4>

    <div class="accordion" id="riwayatPenambahanAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOneRiwayatPenambahan">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOneRiwayatPenambahan" aria-expanded="true" aria-controls="collapseOneRiwayatPenambahan">
                    1. Melihat Riwayat Penambahan
                </button>
            </h2>
            <div id="collapseOneRiwayatPenambahan" class="accordion-collapse collapse show" aria-labelledby="headingOneRiwayatPenambahan" data-bs-parent="#riwayatPenambahanAccordion">
                <div class="accordion-body">
                    Untuk melihat riwayat penambahan barang, buka halaman "Riwayat Penambahan". Anda akan melihat tabel yang menampilkan semua riwayat penambahan barang. Tabel ini mencakup nomor urut, nama barang, jumlah yang ditambahkan, keterangan, dan tanggal penambahan.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwoRiwayatPenambahan">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwoRiwayatPenambahan" aria-expanded="false" aria-controls="collapseTwoRiwayatPenambahan">
                    2. Menghapus Riwayat Penambahan
                </button>
            </h2>
            <div id="collapseTwoRiwayatPenambahan" class="accordion-collapse collapse" aria-labelledby="headingTwoRiwayatPenambahan" data-bs-parent="#riwayatPenambahanAccordion">
                <div class="accordion-body">
                    Anda dapat menghapus riwayat penambahan barang dengan memilih riwayat yang diinginkan menggunakan checkbox di sebelah kiri setiap entri, lalu mengklik tombol "Hapus Terpilih". Konfirmasi penghapusan akan muncul, dan jika Anda yakin, klik "Hapus" untuk mengonfirmasi.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThreeRiwayatPenambahan">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThreeRiwayatPenambahan" aria-expanded="false" aria-controls="collapseThreeRiwayatPenambahan">
                    3. Memilih Semua Riwayat
                </button>
            </h2>
            <div id="collapseThreeRiwayatPenambahan" class="accordion-collapse collapse" aria-labelledby="headingThreeRiwayatPenambahan" data-bs-parent="#riwayatPenambahanAccordion">
                <div class="accordion-body">
                    Untuk memilih semua riwayat, Anda dapat mengklik checkbox "Pilih Semua" di bagian atas tabel. Ini akan mencentang semua checkbox di dalam tabel secara otomatis, memudahkan Anda dalam memilih riwayat yang ingin dihapus.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFourRiwayatPenambahan">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourRiwayatPenambahan" aria-expanded="false" aria-controls="collapseFourRiwayatPenambahan">
                    4. Mencari Riwayat Penambahan
                </button>
            </h2>
            <div id="collapseFourRiwayatPenambahan" class="accordion-collapse collapse" aria-labelledby="headingFourRiwayatPenambahan" data-bs-parent="#riwayatPenambahanAccordion">
                <div class="accordion-body">
                    Anda dapat mencari riwayat penambahan tertentu dengan menggunakan fitur pencarian yang disediakan. Cukup masukkan nama barang atau tanggal pada kolom pencarian, dan tabel akan menampilkan hasil yang relevan secara otomatis.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
