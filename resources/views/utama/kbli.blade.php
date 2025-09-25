@extends('utama.app')

@section('title', 'Klinik Investasi Surabaya - KBLI')

@section('content')
    <div class="w-full py-14 lg:py-[110px] bg-gradient-to-br from-blue-900 to-sky-400 dark:bg-gray-900 "
        data-aos="fade-down" data-aos-duration="1000">
        <div class="mx-auto px-5 xl:px-28 lg:mx-5 text-white items-center">
            <h2 class="text-start text-xl lg:text-3xl font-bold mb-12">Klasifikasi Baku Lapangan Usaha Indonesia (KBLI)</h2>
            <div class="font-semibold">
                <p class="mb-7">Sistem pengelompokan aktivitas ekonomi yang menghasilkan barang atau jasa di Indonesia,
                    disusun oleh Badan Pusat Statistik (BPS) untuk menyamakan standar definisi, data statistik, dan
                    memudahkan pelaku usaha menentukan jenis bisnis Anda
                </p>
                <h5>Fungsi KBLI</h5>
                <ul class="list-inside list-disc">
                    <li>
                        <span class="font-bold">Panduan Bisnis:</span> <span>Membantu pelaku usaha dalam menentukan kategori
                            bidang usaha yang
                            akan
                            dijalankan. </span>
                    </li>
                    <li>
                        <span class="font-bold">Perizinan Berusaha: </span> <span>Menjadi acuan dalam menentukan jenis dan
                            syarat perizinan di sistem OSS.
                        </span>
                    </li>
                    <li>
                        <span class="font-bold">Perencanaan Ekonomi: </span>
                        <span>Memberikan informasi bagi pemerintah untuk
                            memantau, menganalisis, dan merencanakan kebijakan ekonomi di Indonesia.
                        </span>
                    </li>
                </ul>

            </div>

        </div>
    </div>

    <div class="w-full py-5">
        <div class="relative z-40 mx-auto px-5 xl:px-28 lg:mx-5 text-base-content items-center ">
            <div class="flex flex-col lg:flex-row items-center gap-4 relative" data-aos="fade-down" data-aos-duration="1700">
                <!-- Form Pencarian -->
                <form action="{{ route('kbli.search') }}" method="GET" class="flex w-full gap-2">
                    <div class="relative w-full">
                        <input type="text" name="query" id="search-input" placeholder="Pencarian KBLI Sesuai Usaha..."
                            class="input input-bordered w-full shadow-sm dark:text-base-content bg-transparent dark:border-base-content"
                            value="{{ $query ?? '' }}" autocomplete="off" />
                        <!-- Dropdown Suggestion -->
                        <div id="suggestions"
                            class="absolute z-10 w-full mt-1 bg-base-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg hidden">
                            <ul id="suggestions-list" class="max-h-60 overflow-y-auto"></ul>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </div>
    </div>
    @if (isset($kbli))
        <div class="w-full">
            <div id="results" class="mx-auto px-5 pb-4 lg:pb-10 xl:px-28 lg:mx-5 text-base-content items-center" data-aos="fade-down" data-aos-duration="1200">
                <h5 class="font-bold mb-4 text-md sm:text-xl">Ditemukan {{ $kbli->count() }} hasil :</h5>
                @if ($kbli->isEmpty())
                    <div class="alert alert-warning shadow-lg mt-4">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="stroke-current flex-shrink-0 h-5 w-5 sm:h-6 sm:w-6" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-sm sm:text-base">Tidak ditemukan hasil untuk kata kunci:
                                <strong>{{ $query }}</strong></span>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4" data-aos="fade-down" data-aos-duration="1700">
                        @foreach ($kbli as $item)
                            <div
                                class="card card-border bg-base-100 w-full shadow-md transition-transform duration-700 hover:-translate-y-1">
                                <div class="card-body">
                                    <h2 class="card-title font-bold">{{ $item->kode }} - {{ $item->nama }}</h2>
                                    <p class="text-md sm:text-sm">
                                        <strong>Kategori:</strong>
                                        {{ $item->kategoriKbli->nama ?? 'Tidak Ada Kategori' }}<br>
                                        <strong>Ruang Lingkup:</strong>
                                        {{ $item->ruang_lingkup ?? 'Tidak Ada Deskripsi' }}<br>
                                        <strong>Dinas:</strong> {{ $item->dinas->nama ?? 'Tidak Ada Dinas' }}
                                    </p>
                                    <div class="card-actions justify-start">
                                        <a href="{{ route('kbli.show', $item->kbli_id) }}"
                                            class="btn btn-soft btn-primary btn-sm w-24">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <hr class="my-6 border-base-500">

    <!-- SEMUA KBLI PER DINAS -->
    <div class="w-full pb-9">
        <div class="mx-auto px-5 xl:px-28 lg:mx-5 text-base-content items-center">
            <h5 class="mb-3 text-lg sm:text-xl font-bold" data-aos="fade-down" data-aos-duration="1200">📚 Semua KBLI per Dinas</h5>
            <div class="join join-vertical w-full mb-5 shadow-md">
                @foreach ($dinas as $dinas)
                    <div class="collapse collapse-arrow join-item bg-base-100 transition-all duration-300" data-aos="fade-down" data-aos-duration="1500">
                        <input type="checkbox" name="accordion-dinas" />
                        <div class="collapse-title text-base sm:text-lg font-medium">{{ $dinas->nama }}</div>
                        <div class="collapse-content">
                            <ul class="space-y-2 pl-1">
                                @foreach ($dinas->kbli as $kbli)
                                    <li class="flex justify-between items-center">
                                        <div class="text-base lg:text-lg">
                                            <strong>{{ $kbli->kode }}</strong> - {{ $kbli->nama }}
                                        </div>
                                        <a href="{{ route('kbli.show', $kbli->kbli_id) }}"
                                            class="btn btn-soft btn-primary btn-sm text-xs sm:text-sm xl:w-28 h-9">Lihat
                                            Detail</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <script></script>

@endsection
