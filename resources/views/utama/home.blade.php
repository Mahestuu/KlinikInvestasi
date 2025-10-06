@extends('utama.app')

@section('title', 'Klinik Investasi Surabaya - Home')

@section('content')

    <!-- SLIDER -->
    <section
        class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900">
        <div class="absolute inset-0 bg-black/20 z-0 dark:bg-black/40"></div>
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl dark:bg-blue-600/5"></div>
            <div class="absolute -bottom-20 -right-10 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl dark:bg-indigo-600/5">
            </div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-white/5 rounded-full blur-3xl dark:bg-white/2">
            </div>
        </div>

        <!-- SLIDER UTAMA - PERBAIKAN OVERFLOW -->
        <div
            class="swiper mySwiper carouselUtama swiper-container swiper1 w-full h-[300px] lg:h-[70vh] relative mx-auto z-10 overflow-hidden">
            <div class="swiper-wrapper">
                <div class="swiper-slide w-full h-full relative overflow-hidden">
                    <img src="{{ asset('images/bg_banner1.jpg') }}" alt="Panduan Perizinan Berusaha" loading="lazy"
                        class="w-full h-full object-cover absolute top-0 left-0 transition-transform duration-700 ease-out">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-5 dark:from-black/90">
                    </div>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                        <h2 class="text-base md:text-2xl lg:text-6xl font-bold drop-shadow-md">PANDUAN PERIZINAN KEWENANGAN
                            KOTA
                            SURABAYA
                        </h2>
                        <p class="text-xs md:text-sm lg:text-xl lg:mt-3 drop-shadow-md">Temukan informasi lengkap untuk
                            memulai
                            usaha Anda</p>
                    </div>
                </div>
                <div class="swiper-slide w-full h-full relative overflow-hidden">
                    <img src="{{ asset('images/bg_banner2.jpg') }}" alt="Cari Perizinan KBLI" loading="lazy"
                        class="w-full h-full object-cover absolute top-0 left-0 transition-transform duration-700 ease-out">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent z-5 dark:from-black/80">
                    </div>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                        <h2 class="text-base md:text-2xl lg:text-6xl font-bold drop-shadow-md">CARI PERSYARATAN PERIZINAN
                            DENGAN
                            MUDAH</h2>
                        <p class="text-xs md:text-sm lg:text-xl lg:mt-3 drop-shadow-md">Temukan persyaratan lengkap yang
                            dapat
                            membantu
                            melengkapi proses perizinan sesuai yang Anda butuhkan</p>
                    </div>
                </div>
                <div class="swiper-slide w-full h-full relative overflow-hidden">
                    <img src="{{ asset('images/bg_banner3.jpg') }}" alt="Slide Ketiga" loading="lazy"
                        class="w-full h-full object-cover absolute top-0 left-0 transition-transform duration-700 ease-out">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent z-5 dark:from-black/80">
                    </div>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                        <h2 class="text-xl md:text-3xl lg:text-5xl xl:text-6xl font-bold mb-3 lg:mb-5 animate-fade-in-up">
                            INVESTASI DI SURABAYA</h2>
                        <p class="text-sm md:text-base lg:text-xl opacity-90 animate-fade-in-up delay-200">Kota metropolitan
                            dengan peluang investasi tak terbatas</p>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination swiper-pagination1 !bottom-4 lg:!bottom-8"></div>
            <div
                class="swiper-button-next !text-white !w-10 !h-10 lg:!w-14 lg:!h-14 after:!text-xl lg:after:!text-3xl !right-4 lg:!right-8">
            </div>
            <div
                class="swiper-button-prev !text-white !w-10 !h-10 lg:!w-14 lg:!h-14 after:!text-xl lg:after:!text-3xl !left-4 lg:!left-8">
            </div>
        </div>
    </section>

    <!-- LAYANAN KAMI SECTION - PERBAIKAN OVERFLOW -->
    <section
        class="relative py-12 lg:py-20 bg-gradient-to-b from-white to-blue-50 dark:from-gray-900 dark:to-gray-800 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-transparent to-white/80 dark:to-gray-900/80">
        </div>
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center mb-10 lg:mb-16">
                <h2 class="text-2xl lg:text-4xl font-bold text-blue-900 dark:text-white mb-4" data-aos="fade-up">Layanan
                    Unggulan Kami</h2>
                <p class="text-gray-600 dark:text-gray-300 text-base lg:text-lg" data-aos="fade-up" data-aos-delay="100">
                    Kami menyediakan
                    berbagai layanan untuk memudahkan proses perizinan dan investasi Anda di Kota Surabaya</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-4 lg:p-8 max-w-6xl mx-auto -mt-10 lg:-mt-12 relative z-20 overflow-hidden"
                data-aos="fade-up" data-aos-duration="1000">
                <div class="swiper mySwiper cardSwiper swiper-container swiper2 w-full">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="relative w-full h-64 lg:h-80 rounded-2xl overflow-hidden group">
                                <img src="{{ asset('images/images_3.jpg') }}" alt="Konsultasi Perizinan"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-blue-900/30 to-transparent z-5 dark:from-blue-900/90">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 text-white z-10 transform transition-transform duration-300 group-hover:-translate-y-2">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-yellow-500 flex items-center justify-center mr-3">
                                            <i class="fas fa-comments text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-xl lg:text-2xl font-bold">Konsultasi Perizinan</h3>
                                    </div>
                                    <p class="text-sm lg:text-base opacity-90">Memberikan konsultasi dan pendampingan
                                        terkait proses perizinan berusaha berbasis OSS, serta perizinan non-berusaha dan
                                        non-perizinan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="relative w-full h-64 lg:h-80 rounded-2xl overflow-hidden group">
                                <img src="{{ asset('images/images_2.jpg') }}" alt="Pendampingan LKPM"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-blue-900/30 to-transparent z-5 dark:from-blue-900/90">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 text-white z-10 transform transition-transform duration-300 group-hover:-translate-y-2">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-yellow-500 flex items-center justify-center mr-3">
                                            <i class="fas fa-chart-line text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-xl lg:text-2xl font-bold">Pendampingan LKPM</h3>
                                    </div>
                                    <p class="text-sm lg:text-base opacity-90">Membantu pelaku usaha dalam mengisi Laporan
                                        Kegiatan Penanaman Modal (LKPM) melalui sistem OSS.</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="relative w-full h-64 lg:h-80 rounded-2xl overflow-hidden group">
                                <img src="{{ asset('images/images_1.jpg') }}" alt="Informasi Investasi"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-blue-900/30 to-transparent z-5 dark:from-blue-900/90">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 right-0 p-6 text-white z-10 transform transition-transform duration-300 group-hover:-translate-y-2">
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-yellow-500 flex items-center justify-center mr-3">
                                            <i class="fas fa-info-circle text-white text-lg"></i>
                                        </div>
                                        <h3 class="text-xl lg:text-2xl font-bold">Informasi Investasi</h3>
                                    </div>
                                    <p class="text-sm lg:text-base opacity-90">Memberikan informasi terkait potensi
                                        investasi dan peta peruntukan investasi di Kota Surabaya.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination swiper-pagination2 mt-4"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- TENTANG KAMI SECTION -->
    <section class="py-12 lg:py-20 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="w-full lg:w-1/2 p-4 lg:p-8 order-2 lg:order-1" data-aos="fade-right">
                    <div class="relative">
                        <img src="{{ asset('images/images_8.png') }}" alt="Klinik Investasi Surabaya"
                            class="w-full h-64 lg:h-96 object-contain rounded-lg shadow-lg transform hover:scale-105 transition-transform duration-500">
                        <div
                            class="absolute -bottom-4 -right-4 w-24 h-24 bg-yellow-400 rounded-full opacity-20 dark:opacity-10">
                        </div>
                        <div
                            class="absolute -top-4 -left-4 w-16 h-16 bg-blue-900 rounded-full opacity-20 dark:bg-blue-700 dark:opacity-10">
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 p-4 lg:p-8 order-1 lg:order-2" data-aos="fade-left">
                    <h2 class="text-2xl lg:text-4xl font-bold text-blue-900 dark:text-white mb-4 lg:mb-6">Klinik Investasi
                        Pemerintah Kota
                        Surabaya</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-base lg:text-lg mb-6">Pusat layanan dan pendampingan
                        yang didirikan oleh
                        Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kota Surabaya untuk mempermudah
                        investor dan pelaku usaha dalam memahami peluang investasi serta mengurus segala perizinan dan
                        layanan lainnya yang dibutuhkan.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Layanan konsultasi gratis untuk pelaku
                                usaha</p>
                        </div>
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Pendampingan proses perizinan dari awal
                                hingga selesai</p>
                        </div>
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Informasi terupdate tentang regulasi dan
                                kebijakan</p>
                        </div>
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">Akses mudah melalui sistem online dan
                                offline</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEPUTAR INFORMASI-->
    <div
        class="w-full py-12 lg:py-20 bg-gradient-to-br from-blue-800 to-blue-600 dark:from-gray-800 dark:to-gray-700 text-white overflow-hidden">
        <div class="mx-auto px-5 xl:px-28">
            <h2 class="text-center text-2xl lg:text-4xl font-bold mb-4" data-aos="fade-down">Seputar Informasi</h2>
            <p class="text-center max-w-2xl mx-auto mb-10 lg:mb-16 opacity-90" data-aos="fade-down" data-aos-delay="200">
                Informasi penting yang perlu Anda ketahui sebelum memulai usaha di Surabaya</p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="group bg-white/10 dark:bg-white/5 backdrop-blur-sm p-6 rounded-2xl hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 cursor-pointer"
                    data-aos="fade-up" data-aos-duration="1000">
                    <a href="https://oss.go.id/informasi/kbli-berbasis-risiko">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="bg-yellow-500 p-4 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-book-open fa-3x text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">KBLI</h3>
                            <p class="text-sm lg:text-base opacity-90 mb-4">KBLI adalah pengklasifikasian
                                aktivitas/kegiatan
                                ekonomi Indonesia yang menghasilkan produk/output, baik berupa barang maupun jasa,
                                berdasarkan
                                lapangan usaha.</p>
                            <button class="text-yellow-400 font-semibold text-sm flex items-center mt-auto">
                                Pelajari Selengkapnya
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </a>
                </div>

                <div class="group bg-white/10 dark:bg-white/5 backdrop-blur-sm p-6 rounded-2xl hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 cursor-pointer"
                    data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <a href="https://oss.go.id/informasi/pb-umku">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="bg-blue-400 p-4 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-swatchbook fa-3x text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">PB UMKU</h3>
                            <p class="text-sm lg:text-base opacity-90 mb-4">Perizinan Berusaha Untuk Menunjang Kegiatan
                                Usaha
                                (PB UMKU) adalah perizinan yang diperlukan bagi kegiatan usaha dan/atau produk pada saat
                                pelaksanaan tahap operasional dan/atau komersial.</p>
                            <button class="text-yellow-400 font-semibold text-sm flex items-center mt-auto">
                                Pelajari Selengkapnya
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </a>
                </div>

                <div class="group bg-white/10 dark:bg-white/5 backdrop-blur-sm p-6 rounded-2xl hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 cursor-pointer"
                    data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="bg-green-500 p-4 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid fa-folder-open fa-3x text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3">NIB</h3>
                        <p class="text-sm lg:text-base opacity-90 mb-4">Nomor Identitas resmi dan satu-satunya yang
                            dimiliki oleh setiap pelaku usaha di Indonesia untuk menjalankan kegiatan usahanya secara legal
                            dan terintegrasi melalui sistem OSS.</p>
                        <button class="text-yellow-400 font-semibold text-sm flex items-center mt-auto">
                            Pelajari Selengkapnya
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TESTIMONI SECTION -->
    <section class="py-12 lg:py-20 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="max-w-4xl mx-auto text-center mb-10 lg:mb-16">
                <h2 class="text-2xl lg:text-4xl font-bold text-blue-900 dark:text-white mb-4" data-aos="fade-down">Apa
                    Kata Mereka?</h2>
                <p class="text-gray-600 dark:text-gray-300 text-base lg:text-lg" data-aos="fade-down"
                    data-aos-delay="100">Testimoni dari
                    pelaku usaha yang telah menggunakan layanan Klinik Investasi Surabaya</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="bg-blue-50 dark:bg-gray-800 rounded-2xl p-6 shadow-md" data-aos="fade-up"
                    data-aos-duration="1000">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-700 flex items-center justify-center text-blue-800 dark:text-blue-200 font-bold text-lg mr-3">
                            AS
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 dark:text-white">Ahmad Surya</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Pemilik UMKM</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm lg:text-base">"Layanan Klinik Investasi sangat
                        membantu saya dalam
                        mengurus perizinan usaha. Prosesnya cepat dan petugasnya ramah."</p>
                    <div class="flex text-yellow-400 mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-gray-800 rounded-2xl p-6 shadow-md" data-aos="fade-up"
                    data-aos-duration="1000" data-aos-delay="100">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-700 flex items-center justify-center text-blue-800 dark:text-blue-200 font-bold text-lg mr-3">
                            DS
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 dark:text-white">Diana Sari</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Direktur Perusahaan</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm lg:text-base">"Pendampingan untuk LKPM sangat detail
                        dan membantu. Tim
                        Klinik Investasi profesional dalam memberikan solusi."</p>
                    <div class="flex text-yellow-400 mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-gray-800 rounded-2xl p-6 shadow-md" data-aos="fade-up"
                    data-aos-duration="1000" data-aos-delay="200">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-700 flex items-center justify-center text-blue-800 dark:text-blue-200 font-bold text-lg mr-3">
                            RB
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 dark:text-white">Rizki Budi</h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Investor</p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm lg:text-base">"Informasi investasi yang diberikan
                        sangat lengkap dan
                        update. Membantu saya dalam mengambil keputusan investasi."</p>
                    <div class="flex text-yellow-400 mt-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION - NEW -->
    <div
        class="w-full py-12 lg:py-20 bg-gradient-to-r from-blue-600 to-blue-800 dark:from-gray-700 dark:to-gray-800 text-white overflow-hidden">
        <div class="mx-auto px-5 xl:px-28 text-center">
            <h2 class="text-2xl lg:text-4xl font-bold mb-4" data-aos="fade-down">Siap Memulai Usaha Anda di Surabaya?</h2>
            <p class="text-lg lg:text-xl max-w-3xl mx-auto mb-8 opacity-90" data-aos="fade-down" data-aos-delay="200">
                Kunjungi kami sekarang untuk konsultasi gratis dan dapatkan panduan lengkap perizinan usaha</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="300">
                <a href="https://maps.app.goo.gl/Lq1ZudbmVkiysatf9">
                    <button
                        class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-blue-900 font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg cursor-pointer">
                        <i class="fas fa-map-marker-alt text-xl mr-2"></i>Kunjungi Kami
                    </button>
                </a>
            </div>

            <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-12 max-w-2xl mx-auto"
                data-aos="fade-up" data-aos-delay="500">
                <div class="flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-2xl mr-3 text-yellow-400"></i>
                    <div>
                        <p class="font-semibold">Lokasi Kami</p>
                        <p class="text-sm opacity-80">Mal Pelayanan Publik Siola Lt. 1<br>Jl. Tunjungan No.1-3</p>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <i class="fas fa-envelope text-2xl mr-3 text-yellow-400"></i>
                    <div>
                        <p class="font-semibold">Email</p>
                        <p class="text-sm opacity-80">klinikinvestasisurabaya@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ SECTION - NEW -->
    <div class="w-full py-12 lg:py-20 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="mx-auto px-5 xl:px-28">
            <h2 class="text-center text-2xl lg:text-4xl font-bold mb-4 text-blue-900 dark:text-white"
                data-aos="fade-down">Pertanyaan Umum
            </h2>
            <p class="text-center text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mb-10 lg:mb-16" data-aos="fade-down"
                data-aos-delay="200">Temukan jawaban atas pertanyaan yang sering diajukan tentang layanan kami</p>

            <div class="max-w-3xl mx-auto" data-aos="fade-up">
                <div class="space-y-4">
                    <div tabindex="0"
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            Bagaimana cara mengajukan perizinan berusaha?
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>Anda dapat mengajukan perizinan berusaha melalui sistem OSS (Online Single Submission) dengan
                                terlebih dahulu melakukan pendaftaran akun. Tim Klinik Investasi Surabaya siap membantu
                                proses pendaftaran dan pengisian dokumen yang diperlukan.</p>
                        </div>
                    </div>

                    <div
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            Berapa lama proses penerbitan perizinan?
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>Waktu proses penerbitan perizinan bervariasi tergantung jenis perizinan dan kelengkapan
                                dokumen. Secara umum, untuk perizinan sederhana dapat diproses dalam 1-3 hari kerja,
                                sedangkan perizinan yang lebih kompleks membutuhkan waktu 5-14 hari kerja.</p>
                        </div>
                    </div>

                    <div
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            Apakah layanan Klinik Investasi berbayar?
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>Layanan konsultasi dan pendampingan di Klinik Investasi Surabaya sepenuhnya gratis.</p>
                        </div>
                    </div>

                    <div
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            Bagaimana cara menentukan KBLI yang tepat untuk usaha saya?
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>Anda dapat berkonsultasi dengan tim Klinik Investasi untuk menentukan KBLI yang tepat sesuai
                                dengan kegiatan usaha Anda. Kami memiliki panduan lengkap dan terkini tentang KBLI 2023 yang
                                dapat membantu Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
