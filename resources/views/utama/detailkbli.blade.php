@extends('utama.app')

@section('title', 'Klinik Investasi Surabaya - Detail KBLI')

@section('content')
    <div class="w-full py-5">
        <div class="mx-auto px-5 xl:px-28 lg:mx-5">
            <div>
                <a href="{{ route('kbli.index') }}"
                    class="btn btn-soft btn-primary w-[120px] lg:w-[160px] text-left shadow-md">⬅ Kembali</a>
            </div>
        </div>
        <div class="mx-auto px-5 xl:px-28 lg:mx-5 mt-4">
            <div
                class="font-bold bg-gradient-to-r from-blue-800 to-cyan-300 bg-clip-text text-transparent dark:text-white text-2xl uppercase text-center lg:text-start">
                <h4>Klasifikasi Baku Lapangan Usaha Indonesia (KBLI)</h4>
            </div>
            <!-- Card -->
            <div class="card card-border bg-base-100 shadow-md mt-4 mb-4">
                <div class="card-body lg:px-8">
                    <h2 class="card-title text-lg lg:text-xl font-bold">{{ $kbli->kode }} - {{ $kbli->nama }}</h2>
                    <p class="text-base lg:text-lg">
                        <strong>Kategori :</strong> {{ $kbli->kategoriKbli->nama ?? 'Tidak Ada Kategori' }}<br>
                        <strong>Ruang Lingkup :</strong> {{ $kbli->ruang_lingkup ?? 'Tidak Ada Ruang Lingkup' }}<br>
                        <strong>Kewenangan :</strong> Bupati/Walikota<br>
                        <strong>Dinas :</strong> {{ $kbli->dinas->nama ?? 'Tidak Ada Dinas' }}
                    </p>
                    <h5 class="font-bold text-lg lg:text-xl mt-4">📋 Persyaratan Perizinan</h5>
                    @if ($kbli->persyaratanPerizinan->isEmpty())
                        <div class="alert alert-warning alert-soft shadow-lg mt-2">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="stroke-current flex-shrink-0 h-5 w-5 sm:h-6 sm:w-6" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-sm sm:text-base">Tidak ada persyaratan perizinan untuk KBLI ini.</span>
                            </div>
                        </div>
                    @else
                        <ol class="list-decimal list-inside text-md lg:text-lg space-y-2 ml-8">
                            @foreach ($kbli->persyaratanPerizinan as $persyaratan)
                                <li
                                    class="font-bold bg-gradient-to-r from-blue-800 to-cyan-300 bg-clip-text text-transparent dark:text-white">
                                    <span class="">{{ $persyaratan->nama }}</span>
                                    <ul
                                        class="no-underline font-normal text-black dark:text-white list-disc list-inside ml-5 space-y-1">
                                        @foreach ($persyaratan->subpoin as $subpoin)
                                            @php
                                                // Deteksi nomor di awal (contoh: "1. Item 2. Item")
                                                $items = preg_split(
                                                    '/(?=\d+\.\s)/',
                                                    $subpoin->item,
                                                    -1,
                                                    PREG_SPLIT_NO_EMPTY,
                                                );
                                                $items = array_map('trim', $items);
                                            @endphp
                                            @foreach ($items as $item)
                                                @if (!empty($item))
                                                    @php
                                                        // Hapus nomor di awal
                                                        $cleanItem = preg_replace('/^\d+\.\s*/', '', $item);
                                                        // Deteksi URL dan ubah menjadi link
                                                        $urlPattern = '/(https?:\/\/[^\s<>"\']+|www\.[^\s<>"\']+)/i';
                                                        $formattedItem = preg_replace(
                                                            $urlPattern,
                                                            '<a href="$1" class="text-blue-600 hover:underline" target="_blank" rel="noopener noreferrer">$1</a>',
                                                            $cleanItem,
                                                        );
                                                    @endphp
                                                    <li>{!! $formattedItem !!}</li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
