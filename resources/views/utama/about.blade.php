@extends('utama.app')

@section('title', 'Tentang - Klinik Investasi | About')

@section('content')
<section class="bg-base-200 py-8">
    <div class="container mx-auto px-4 sm:px-6 md:px-8">
        <div class="text-center mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-base-content">Tentang Aplikasi</h2>
            <p class="text-sm sm:text-base text-base-content/70 mt-2 max-w-md mx-auto">
                Kenali lebih dalam tentang layanan kami
            </p>
        </div>
        <div class="grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <!-- KBLI -->
            <div class="card bg-base-100 shadow-lg transition-transform duration-300 hover:-translate-y-1">
                <div class="card-body text-center  p-4 sm:p-6">
                    <div class="mb-3">
                        <i class='bx bx-folder-open bx-lg text-primary' aria-label="Ikon KBLI"></i>
                    </div>
                    <h5 class="justify-center card-title text-base sm:text-lg font-bold">KBLI</h5>
                    <p class="text-xs text-base-content/70 xl:text-base ">
                        KBLI adalah pengklasifikasian aktivitas/kegiatan ekonomi Indonesia yang menghasilkan produk/output, baik berupa barang maupun jasa, berdasarkan lapangan usaha untuk memberikan keseragaman konsep, definisi, dan klasifikasi lapangan usaha dalam perkembangan dan pergeseran kegiatan ekonomi di Indonesia.
                    </p>
                </div>
            </div>
            <!-- Klinik Investasi -->
            <div class="card bg-base-100 shadow-lg transition-transform duration-300 hover:-translate-y-1">
                <div class="card-body text-center p-4 sm:p-6">
                    <div class="mb-3">
                        <i class='bx bx-building-house bx-lg text-primary' aria-label="Ikon Klinik Investasi"></i>
                    </div>
                    <h5 class="justify-center card-title text-base sm:text-lg font-bold">Klinik Investasi</h5>
                    <p class="text-xs text-base-content/70 xl:text-base">
                        Klinik Investasi bertujuan untuk memberikan pendampingan langsung kepada pelaku usaha dan pemerintah daerah terkait pelaporan kegiatan penanaman modal, pemecahan permasalahan investasi, serta upaya peningkatan capaian realisasi investasi di wilayah Jawa Timur.
                    </p>
                </div>
            </div>
            <!-- Author -->
            <div class="card bg-base-100 shadow-lg transition-transform duration-300 hover:-translate-y-1">
                <div class="card-body text-center p-4 sm:p-6">
                    <div class="mb-3">
                        <i class='bx bx-user-circle bx-lg text-primary' aria-label="Ikon Author"></i>
                    </div>
                    <h5 class="justify-center card-title text-base sm:text-lg font-bold">Author</h5>
                    <p class="text-xs xl:text-base">
                        Laman ini dibuat oleh Tim Klinik Investasi Surabaya bulan Juli 2025 serta mahasiswa magang dari prodi Teknologi Sains Data Universitas Airlangga.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection