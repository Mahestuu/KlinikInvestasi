@extends('utama.app')

@section('title', 'Klinik Investasi Surabaya - PBUMKU')

@section('content')
    <div class="w-full py-14 lg:py-[100px] bg-gradient-to-br from-blue-900 to-sky-400 dark:bg-gray-900 " data-aos="fade-down"
        data-aos-duration="1000">
        <div class="mx-auto px-5 xl:px-28 lg:mx-5 text-white items-center">
            <h2 class="text-start text-xl lg:text-3xl font-bold mb-12">Perizinan Berusaha Untuk Menunjang Kegiatan Usaha
                (PB UMKU)</h2>
            <div class="font-semibold">
                <p class="mb-7">Perizinan Berusaha untuk Menunjang Kegiatan Usaha (PB-UMKU) adalah bentuk perizinan yang
                    wajib
                    dimiliki dalam
                    menjalankan usaha dan/atau produk ketika memasuki tahap operasional maupun komersial.
                </p>
                <p class="mb-7">PB-UMKU memiliki banyak jenis, seperti Izin, Persetujuan, Penetapan, Pengesahan,
                    Penunjukan,
                    Registrasi,
                    Rekomendasi, Sertifikat, Sertifikasi, Konsultasi, hingga Surat Keterangan.</p>
                <p> Perlu dicatat, PB-UMKU tidak termasuk perizinan yang hanya berlaku satu kali (bersifat transaksional),
                    misalnya Izin Penerbangan bagi Pesawat, Pilot, Awak Kabin, atau Persetujuan Impor/Ekspor.</p>
            </div>

        </div>
    </div>
    <div class="w-full py-5">
        <div class="mx-auto px-5 xl:px-28 lg:mx-5 text-base-content items-center">
            <div class="flex flex-col lg:flex-row items-center gap-4">
                <!-- Dropdown Pilih Dinas -->
                <div class="dropdown relative z-40 " data-aos="fade-down" data-aos-duration="1300">
                    <div tabindex="0" role="button"
                        class="btn btn-soft btn-primary w-sm shadow-sm dark:text-base-content dark:border-base-content hover:bg-blue-700 hover:text-white text-left justify-start">
                        {{ $selectedDinas ? $dinas->find($selectedDinas)->nama ?? 'Semua Dinas' : 'Semua Dinas' }}
                    </div>
                    <ul tabindex="0"
                        class="dropdown-content menu w-sm bg-base-100 dark:bg-gray-900 rounded-box z-25 p-2 shadow-md">
                        <li><a href="{{ route('pbumku.index') }}"
                                class="text-base-content hover:text-white hover:bg-blue-600 {{ !$selectedDinas ? 'bg-blue-600 text-white' : '' }}">Semua
                                Dinas</a></li>
                        @foreach ($dinas as $dina)
                            <li><a href="{{ route('pbumku.index', ['dinas_id' => $dina->dinas_id]) }}"
                                    class="text-base-content hover:text-white hover:bg-blue-600 {{ $selectedDinas == $dina->dinas_id ? 'bg-blue-600 text-white' : '' }}">{{ $dina->nama }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Form Pencarian dengan Live Search -->
                <!-- Form Pencarian dengan Live Search -->
                <div class="relative w-full max-w-4xl" data-aos="fade-down" data-aos-duration="1500">
                    <form action="{{ route('pbumku.search') }}" method="GET" class="flex w-full gap-2">
                        <input type="text" name="query" id="search-input" placeholder="Cari PBUMKU berdasarkan nama..."
                            class="input input-bordered w-full shadow-sm dark:text-base-content bg-transparent dark:border-base-content"
                            value="{{ $query ?? '' }}" autocomplete="off"
                            data-live-search-url="{{ route('pbumku.live-search') }}" />
                        @if ($selectedDinas)
                            <input type="hidden" name="dinas_id" id="dinas_id" value="{{ $selectedDinas }}">
                        @endif
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                    <ul id="search-suggestions"
                        class="dropdown-content menu w-full bg-base-100 dark:bg-gray-900 rounded-box z-10 p-2 shadow hidden absolute top-full mt-1">
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full">
        <div class="mx-auto px-5 pb-4 lg:pb-10 xl:px-28 lg:mx-5 text-base-content items-center">
            @if ($pbumku->isEmpty())
                <div class="alert alert-warning shadow-lg mt-4" data-aos="fade-down" data-aos-duration="1000">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-5 w-5 sm:h-6 sm:w-6"
                            fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-sm sm:text-base">Tidak ditemukan hasil untuk pencarian
                            <strong>{{ $query ?? 'Semua' }}</strong>.</span>
                    </div>
                </div>
            @else
                <h5 class="font-bold mb-4 text-md sm:text-xl" data-aos="fade-down" data-aos-duration="1000">Ditemukan {{ $pbumku->total() }} hasil:</h5>
                <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-1 gap-4">
                    @foreach ($pbumku as $item)
                        <div
                            class="card card-border bg-base-100 shadow-md transition-transform duration-700 hover:-translate-y-1" data-aos="fade-down" data-aos-duration="1300">
                            <div class="card card-title items-start font-bold px-8 py-6 bg-blue-200 dark:bg-gray-800/50">
                                {{ $item->nama }}
                            </div>
                            <div class="card-body px-8">
                                <div class="card font-semibold text-base">
                                    KBLI Terkait:
                                    {{ $item->kbli->isEmpty() ? 'Tidak Ada KBLI' : $item->kbli->pluck('kode')->join(' ') }}
                                </div>
                                <hr class="my-4 border-base-400/30">
                                <div class="flex flex-row justify-between items-center">
                                    <h2 class="font-semibold text-base">Status: Tersedia</h2>
                                    <a href="{{ route('pbumku.show', ['pbumku_id' => $item->pbumku_id]) }}"
                                        class="btn btn-soft btn-primary btn-sm w-24">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 flex justify-center" data-aos="fade-down" data-aos-duration="1000">
                    <div class="join">
                        <!-- Previous Button -->
                        <a href="{{ $pbumku->previousPageUrl() ? $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->previousPageUrl() : '#' }}"
                            class="join-item btn btn-outline btn-primary btn-sm {{ $pbumku->onFirstPage() ? 'btn-disabled' : '' }}">Previous</a>
                        <!-- Page Numbers -->
                        @php
                            $currentPage = $pbumku->currentPage();
                            $lastPage = $pbumku->lastPage();
                            $range = 2; // Show 2 pages before and after current page
                            $start = max(1, $currentPage - $range);
                            $end = min($lastPage, $currentPage + $range);
                        @endphp
                        @for ($i = $start; $i <= $end; $i++)
                            <a href="{{ $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->url($i) }}"
                                class="join-item btn btn-outline btn-primary btn-sm {{ $currentPage == $i ? 'btn-active' : '' }}">{{ $i }}</a>
                        @endfor
                        <!-- Next Button -->
                        <a href="{{ $pbumku->nextPageUrl() ? $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->nextPageUrl() : '#' }}"
                            class="join-item btn btn-outline btn-primary btn-sm {{ $pbumku->hasMorePages() ? '' : 'btn-disabled' }}">Next</a>
                    </div>
                </div>
            @endif
        </div>
    </div>


@endsection
