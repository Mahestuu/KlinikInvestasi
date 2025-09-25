@extends('utama.app')

@section('title', 'Klinik Investasi Surabaya - Home')

@section('content')

    <!-- SLIDER -->
    <div class="swiper mySwiper carouselUtama swiper-container swiper1 w-full h-[200px] lg:h-[685px] relative mx-auto">
        <div class="swiper-wrapper " data-aos="zoom-in-down">
            <div class="swiper-slide w-full h-full relative overflow-hidden">
                <img src="{{ asset('images/bg_banner1.jpg') }}" alt="Panduan Perizinan Berusaha" loading="lazy"
                    class="w-full h-full object-cover absolute top-0 left-0">
                <div class="absolute inset-0 bg-gray-900/40 z-5"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                    <h2 class="text-base md:text-2xl lg:text-6xl font-bold drop-shadow-md">PANDUAN PERIZINAN KEWENANGAN KOTA
                        SURABAYA
                    </h2>
                    <p class="text-xs md:text-sm lg:text-xl lg:mt-3 drop-shadow-md">Temukan informasi lengkap untuk memulai
                        usaha Anda</p>
                </div>
            </div>
            <div class="swiper-slide w-full h-full relative overflow-hidden">
                <img src="{{ asset('images/bg_banner2.jpg') }}" alt="Cari Perizinan KBLI" loading="lazy"
                    class="w-full h-full object-cover absolute top-0 left-0">
                <div class="absolute inset-0 bg-gray-900/40 z-5"></div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                    <h2 class="text-base md:text-2xl lg:text-6xl font-bold drop-shadow-md">CARI PERSYARATAN PERIZINAN DENGAN
                        MUDAH</h2>
                    <p class="text-xs md:text-sm lg:text-xl lg:mt-3 drop-shadow-md">Temukan persyaratan lengkap yang dapat
                        membantu
                        melengkapi proses perizinan sesuai yang Anda butuhkan</p>
                </div>
            </div>
            <div class="swiper-slide w-full h-full relative overflow-hidden">
                <img src="{{ asset('images/bg_banner3.jpg') }}" alt="Slide Ketiga" loading="lazy"
                    class="w-full h-full object-cover absolute top-0 left-0">
                <div class="absolute inset-0 bg-gray-900/40 z-5"></div>
            </div>
        </div>
        <div class="swiper-pagination swiper-pagination1"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="static lg:absolute lg:bottom-[-50vh] left-1/2 lg:-translate-x-1/2 z-20 card bg-base-100 w-11/12 sm:w-3/4 md:w-2/3 lg:w-[86%] h-[17rem] lg:h-[55vh] shadow-lg rounded-3xl overflow-x-hidden mt-4 lg:mt-0 mx-auto lg:mx-0"
        data-aos="fade-down" data-aos-duration="1300">
        <div class="card-body p-4">
            <div class="card-body items-center text-center">
                <h2 class="card-title font-bold text-xl mt-[-20px] lg:text-2xl lg:mb-2 lg:mt-[-10px]">Layanan Kami</h2>
                <div class="swiper mySwiper cardSwiper swiper-container swiper2 w-full">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="relative sm:w-[270px] h-44 lg:w-[600px] lg:h-[293px] flex-shrink-0">
                                <img src="{{ asset('images/images_3.jpg') }}" alt="Card Image 1"
                                    class="w-full h-full object-cover rounded-md">
                                <div class="absolute inset-0 bg-blue-600/30 z-5 rounded-md"></div>
                                <div class="absolute bottom-10 left-5 text-white text-start z-10 max-w-[70%] p-2">
                                    <h2 class="text-sm md:text-sm lg:text-2xl font-bold drop-shadow-md text-yellow-400">
                                        Konsultasi
                                        Perizinan
                                    </h2>
                                    <p class="text-[10px] md:text-[10px] lg:text-base mt-1 drop-shadow-md text-wrap">
                                        Memberikan konsultasi dan
                                        pendampingan terkait proses perizinan berusaha berbasis OSS, serta perizinan
                                        non-berusaha dan non-perizinan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="relative sm:w-[270px] lg:w-[600px] h-44 lg:h-[293px] flex-shrink-0">
                                <img src="{{ asset('images/images_2.jpeg') }}" alt="Card Image 2"
                                    class="w-full h-full object-cover rounded-md">
                                <div class="absolute inset-0 bg-blue-600/50 z-5 rounded-md"></div>
                                <div class="absolute bottom-10 left-5 text-white text-start z-10 max-w-[65%] p-2">
                                    <h2 class="text-sm md:text-sm lg:text-2xl font-bold drop-shadow-md text-yellow-400">
                                        Pendampingan
                                        LKPM
                                    </h2>
                                    <p class="text-xs md:text-xs lg:text-base mt-1 drop-shadow-md">Membantu pelaku usaha
                                        dalam
                                        mengisi Laporan Kegiatan Penanaman Modal (LKPM) melalui sistem OSS.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="relative sm:w-[270px]  lg:w-[600px] h-44 lg:h-[293px] flex-shrink-0">
                                <img src="{{ asset('images/images_1.jpeg') }}" alt="Card Image 3"
                                    class="w-full h-full object-cover rounded-md">
                                <div class="absolute inset-0 bg-blue-600/35 z-5 rounded-md"></div>
                                <div class="absolute bottom-10 left-5 text-white text-start z-10 max-w-[65%] p-2">
                                    <h2 class="text-sm md:text-base lg:text-2xl font-bold drop-shadow-md text-yellow-400">
                                        Informasi Investasi
                                    </h2>
                                    <p class="text-xs md:text-xs lg:text-base mt-1 drop-shadow-md">Memberikan informasi
                                        terkait
                                        potensi investasi dan peta peruntukan investasi di Kota Surabaya.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination swiper-pagination2 mt-2"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="mt-2 mx-auto px-5 xl:px-28 lg:mt-95">
        <div class="flex flex-col lg:flex-row items-center">
            <div class="w-full text-center lg:text-start lg:w-1/2 p-4 ">
                <h2 class="text-xl lg:text-5xl font-bold" data-aos="fade-right">Klinik Investasi Pemerintah Kota Surabaya
                </h2>
                <p class="text-sm mt-5 lg:text-lg lg:mt-10 " data-aos="fade-right">Pusat layanan dan pendampingan yang
                    didirikan oleh Dinas
                    Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kota Surabaya untuk mempermudah investor dan
                    pelaku usaha dalam memahami peluang investasi serta mengurus segala perizinan dan layanan lainnya yang
                    dibutuhkan Surabaya</p>
            </div>
            <div class="mt-[-20px] lg:mt-2 lg:w-[1/2] p-4" data-aos="fade-left">
                <img src="{{ asset('images/images_8.png') }}" alt="Klinik Investasi Surabaya"
                    class="w-full h-48 lg:w-[600px] lg:h-[600px] object-contain rounded-md mx-auto ">
            </div>
        </div>
    </div>

    {{-- <hr class="my-6 border-base-500"> --}}

    <div class="w-full mt-[-15px] lg:mt-[-15px] bg-base-100 py-8 lg:pb-20">
        <div class="mx-auto px-5 xl:px-28">
            <h2 class="text-center text-xl lg:text-2xl font-bold mb-6" data-aos="fade-down">Seputar Informasi</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="card bg-yellow-400 shadow-md p-4 items-center text-center rounded-xl transition-transform duration-700 hover:-translate-y-1 hover:bg-gradient-to-br from-yellow-400 to-orange-400 hover:text-white"
                    data-aos="fade-down" data-aos-duration="1200" data-aos-easing="linear">
                    <div class="card-body ">
                        <i class="fa-solid fa-book-open fa-5x mb-3"></i>
                        {{-- <h3 class="text-lg font-bold">KBLI</h3> --}}
                        <p class="text-base">KBLI adalah pengklasifikasian aktivitas/kegiatan ekonomi Indonesia yang
                            menghasilkan produk/output, baik berupa barang maupun jasa, berdasarkan lapangan usaha untuk
                            memberikan keseragaman konsep, definisi, dan klasifikasi lapangan usaha dalam perkembangan dan
                            pergeseran kegiatan ekonomi di Indonesia.</p>
                    </div>
                </div>
                <div class="card text-white bg-blue-900 shadow-md p-4 items-center text-center rounded-xl transition-transform duration-700 hover:-translate-y-1 hover:bg-gradient-to-br from-blue-900 to-sky-300"
                    data-aos="fade-down" data-aos-duration="1700" data-aos-easing="linear">
                    <div class="card-body ">
                        <div class="">
                            <i class="fa-solid fa-swatchbook fa-5x mb-3"></i>
                        </div>
                        {{-- <h3 class="text-lg font-bold">PB UMKU</h3> --}}
                        <p class="text-base">Perizinan Berusaha Untuk Menunjang Kegiatan Usaha (PB UMKU) adalah perizinan
                            yang diperlukan bagi kegiatan usaha dan/atau produk pada saat pelaksanaan tahap operasional
                            dan/atau komersial.</p>
                    </div>
                </div>
                <div class="card bg-yellow-400 shadow-md p-4 items-center text-center place-items-center rounded-xl transition-transform duration-700 hover:-translate-y-1 hover:bg-gradient-to-br from-yellow-400 to-orange-400 hover:text-white"
                    data-aos="fade-down" data-aos-duration="2000" data-aos-easing="linear">
                    <div class="card-body ">
                        <div class="">
                            <i class="fa-solid fa-folder-open fa-5x mb-3"></i>
                        </div>
                        {{-- <h3 class="text-lg font-bold">NIB</h3> --}}
                        <p class="text-base">Nomor Identitas resmi dan satu-satunya yang dimiliki oleh setiap pelaku usaha
                            di Indonesia untuk menjalankan kegiatan usahanya secara legal dan terintegrasi melalui sistem
                            Online Single Submission (OSS).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEMUA KBLI PER DINAS -->

@endsection
