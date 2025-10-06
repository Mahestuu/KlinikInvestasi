@extends('utama.app')

@section('title', 'Klinik Investasi Surabaya - Detail KBLI')

@section('content')
    <!-- HERO SECTION -->
    <section
        class="relative w-full py-8 lg:py-12 bg-gradient-to-br from-blue-800 to-blue-600 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-black/20 z-0 dark:bg-black/40"></div>
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-white/5 rounded-full blur-3xl">
            </div>
        </div>

        <div class="relative z-10 mx-auto px-5 xl:px-28">
            <!-- Back Button -->
            <div class="mb-8" data-aos="fade-right">
                <a href="{{ route('kbli.index') }}"
                    class="inline-flex items-center space-x-3 px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl backdrop-blur-sm transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left"></i>
                    <span class="font-semibold">Kembali ke Pencarian</span>
                </a>
            </div>

            <!-- Main Header -->
            <div class="text-center lg:text-left" data-aos="fade-up">
                <div class="mb-4">
                    <span
                        class="inline-block px-4 py-2 bg-yellow-500 text-blue-900 rounded-full lg:text-lg font-semibold mb-3">
                        Kode KBLI: {{ $kbli->kode }}
                    </span>
                    <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                        {{ $kbli->nama }}
                    </h1>
                    <p class="text-xl text-blue-100 max-w-3xl">
                        Klasifikasi Baku Lapangan Usaha Indonesia
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT SECTION -->
    <section class="w-full py-8 lg:py-12 bg-white dark:bg-gray-900">
        <div class="mx-auto px-5 xl:px-28">
            <div class="max-w-6xl mx-auto">
                <!-- Quick Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" data-aos="fade-up">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-layer-group text-xl"></i>
                            </div>
                            <div>
                                <p class="text-blue-100 text-sm">Kategori</p>
                                <p class="font-bold text-lg">{{ $kbli->kategoriKbli->nama ?? 'Umum' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                            <div>
                                <p class="text-green-100 text-sm">Dinas Terkait</p>
                                <p class="font-bold text-lg">{{ $kbli->dinas->nama ?? 'Tidak Terkait' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-shield text-xl"></i>
                            </div>
                            <div>
                                <p class="text-purple-100 text-sm">Kewenangan</p>
                                <p class="font-bold text-lg">Bupati/Walikota</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tasks text-xl"></i>
                            </div>
                            <div>
                                <p class="text-orange-100 text-sm">Persyaratan</p>
                                <p class="font-bold text-lg">{{ $kbli->persyaratanPerizinan->count() }} Item</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Card -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="200">
                    <!-- Left Column - Ruang Lingkup -->
                    <div class="lg:col-span-2">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 lg:p-8">
                            <div class="flex items-center space-x-3 mb-6">
                                <div
                                    class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-bullseye text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Ruang Lingkup</h3>
                            </div>

                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                                    {{ $kbli->ruang_lingkup ?? 'Tidak ada deskripsi ruang lingkup yang tersedia untuk KBLI ini.' }}
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- Right Column - Quick Actions -->
                    <div class="space-y-6">
                        <!-- Help Card -->
                        <div
                            class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-2xl p-6 border border-yellow-200 dark:border-yellow-800">
                            <h4 class="font-bold text-gray-800 dark:text-white mb-3 flex items-center">
                                <i class="fas fa-question-circle mr-2"></i>
                                Butuh Bantuan?
                            </h4>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                                Konsultasi gratis dengan tim kami untuk informasi lebih lanjut
                            </p>
                            <a href="https://maps.app.goo.gl/Lq1ZudbmVkiysatf9"
                                class="w-full flex items-center justify-center space-x-2 p-3 bg-yellow-500 hover:bg-yellow-600 text-yellow-900 rounded-xl transition-colors font-semibold">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Kunjungi Klinik Investasi</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Persyaratan Perizinan Section -->
                <div class="mt-12" data-aos="fade-up" data-aos-delay="400">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 lg:px-8 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">Persyaratan Perizinan</h3>
                                    <p class="text-blue-100">
                                        Dokumen dan persyaratan yang diperlukan untuk usaha ini
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 lg:p-8">
                            @if ($kbli->persyaratanPerizinan->isEmpty())
                                <div class="text-center py-12">
                                    <div
                                        class="w-20 h-20 mx-auto mb-6 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clipboard-check text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                                        Tidak Ada Persyaratan Khusus
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                                        Untuk KBLI ini tidak terdapat persyaratan perizinan khusus.
                                        Silakan konsultasi dengan dinas terkait untuk informasi lebih lanjut.
                                    </p>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($kbli->persyaratanPerizinan as $index => $persyaratan)
                                        <div
                                            class="group bg-gray-50 dark:bg-gray-700 rounded-xl p-6 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300 border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-12 h-12 bg-blue-500 text-white rounded-xl flex items-center justify-center font-bold text-lg">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                                                        {{ $persyaratan->nama }}
                                                    </h4>

                                                    @if ($persyaratan->subpoin->isNotEmpty())
                                                        <div class="space-y-3">
                                                            @foreach ($persyaratan->subpoin as $subpoin)
                                                                @php
                                                                    $items = preg_split(
                                                                        '/(?=\d+\.\s)/',
                                                                        $subpoin->item,
                                                                        -1,
                                                                        PREG_SPLIT_NO_EMPTY,
                                                                    );
                                                                    $items = array_map('trim', $items);
                                                                @endphp

                                                                @foreach ($items as $itemIndex => $item)
                                                                    @if (!empty($item))
                                                                        @php
                                                                            $cleanItem = preg_replace(
                                                                                '/^\d+\.\s*/',
                                                                                '',
                                                                                $item,
                                                                            );
                                                                            $urlPattern =
                                                                                '/(https?:\/\/[^\s<>"\']+|www\.[^\s<>"\']+)/i';
                                                                            $formattedItem = preg_replace(
                                                                                $urlPattern,
                                                                                '<a href="$1" class="text-blue-600 dark:text-blue-400 hover:underline font-medium" target="_blank" rel="noopener noreferrer">$1</a>',
                                                                                $cleanItem,
                                                                            );
                                                                        @endphp
                                                                        <div class="flex items-start space-x-3">
                                                                            <div
                                                                                class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                                                                <i
                                                                                    class="fas fa-check text-green-600 dark:text-green-400 text-xs"></i>
                                                                            </div>
                                                                            <div
                                                                                class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                                                                {!! $formattedItem !!}
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Navigation Footer -->
                <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-between items-center" data-aos="fade-up">
                    <a href="{{ route('kbli.index') }}"
                        class="flex items-center space-x-3 px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Pencarian</span>
                    </a>

                </div>
            </div>
        </div>
    </section>
@endsection