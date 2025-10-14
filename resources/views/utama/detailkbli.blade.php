@extends('utama.app')

@section('title', __('messages.detailkbli_title'))

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
                <a href="{{ LaravelLocalization::getLocalizedURL(null, route('kbli.index')) }}"
                    class="inline-flex items-center space-x-3 px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl backdrop-blur-sm transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left"></i>
                    <span class="font-semibold">{{ __('messages.back_to_search') }}</span>
                </a>
            </div>

            <!-- Main Header -->
            <div class="text-center lg:text-left" data-aos="fade-up">
                <div class="mb-4">
                    <span
                        class="inline-block px-4 py-2 bg-yellow-500 text-blue-900 rounded-full lg:text-lg font-semibold mb-3">
                        {{ __('messages.kbli_code_label') }}: {{ $kbli->kode }}
                    </span>
                    <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                        {{ $kbli->nama }}
                    </h1>
                    <p class="text-xl text-blue-100 max-w-3xl">
                        {{ __('messages.kbli_classification') }}
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
                                <p class="text-blue-100 text-sm">{{ __('messages.category_label') }}</p>
                                <p class="font-bold text-lg">
                                    {{ $kbli->kategoriKbli->nama ?? __('messages.category_default') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                            <div>
                                <p class="text-green-100 text-sm">{{ __('messages.department_label') }}</p>
                                <p class="font-bold text-lg">{{ $kbli->dinas->nama ?? __('messages.department_default') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-shield text-xl"></i>
                            </div>
                            <div>
                                <p class="text-purple-100 text-sm">{{ __('messages.authority_label') }}</p>
                                <p class="font-bold text-lg">{{ __('messages.authority_value') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tasks text-xl"></i>
                            </div>
                            <div>
                                <p class="text-orange-100 text-sm">{{ __('messages.requirements_label') }}</p>
                                <p class="font-bold text-lg">
                                    {{ __($kbli->persyaratanPerizinan->count() == 1 ? 'messages.requirements_count' : 'messages.requirements_count', ['count' => $kbli->persyaratanPerizinan->count()]) }}
                                </p>
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
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ __('messages.scope_title') }}</h3>
                            </div>

                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                                    {{ $kbli->ruang_lingkup ?? __('messages.scope_default') }}
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
                                {{ __('messages.help_title') }}
                            </h4>
                            <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                                {{ __('messages.help_description') }}
                            </p>
                            <a href="https://maps.app.goo.gl/Lq1ZudbmVkiysatf9"
                                class="w-full flex items-center justify-center space-x-2 p-3 bg-yellow-500 hover:bg-yellow-600 text-yellow-900 rounded-xl transition-colors font-semibold">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ __('messages.visit_clinic') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Persyaratan Perizinan Section -->
                <div class="mt-12" data-aos="fade-up" data-aos-delay="400">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-x-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 lg:px-8 py-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-white">{{ __('messages.requirements_title') }}</h3>
                                    <p class="text-blue-100">
                                        {{ __('messages.requirements_description') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6 lg:p-8">
                            @if ($kbli->persyaratanPerizinan->isEmpty())
                                <div class="text-center py-12">
                                    <div
                                        class="w-20 h-20 mx-auto mb-6 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clipboard-check text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                                    </div>
                                    <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                                        {{ __('messages.no_requirements_title') }}
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                                        {{ __('messages.no_requirements_description') }}
                                    </p>
                                </div>
                            @else
                                <div class="space-y-6">
                                    @foreach ($kbli->persyaratanPerizinan as $index => $persyaratan)
                                        <div
                                            class="group bg-gray-50 dark:bg-gray-700 rounded-xl p-4 sm:p-6 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300 border border-gray-200 dark:border-gray-600 overflow-x-hidden">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-12 h-12 bg-blue-500 text-white rounded-xl flex items-center justify-center font-bold text-lg">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h4
                                                        class="text-xl font-bold text-gray-800 dark:text-white mb-3 break-words">
                                                        {{ $persyaratan->nama }}
                                                    </h4>
                                                    @if ($persyaratan->subpoin->isNotEmpty())
                                                        <div class="space-y-0">
                                                            @foreach ($persyaratan->subpoin as $subpoin)
                                                                @php
                                                                    // Render subpoin.item polos tanpa parsing nomor/huruf/bullet
                                                                    $urlPattern =
                                                                        '/(https?:\/\/[^\s<>"\']+|www\.[^\s<>"\']+)/i';
                                                                    $formattedItem = preg_replace(
                                                                        $urlPattern,
                                                                        '<a href="$1" class="text-blue-600 dark:text-blue-400 hover:underline font-medium break-all" target="_blank" rel="noopener noreferrer">$1</a>',
                                                                        $subpoin->item,
                                                                    );
                                                                @endphp
                                                                <div class="flex items-start space-x-3 ml-0">
                                                                    <div
                                                                        class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                                                        <i
                                                                            class="fas fa-check text-green-600 dark:text-green-400 text-xs"></i>
                                                                    </div>
                                                                    <div
                                                                        class="text-gray-700 dark:text-gray-300 leading-relaxed break-words">
                                                                        {!! $formattedItem !!}
                                                                    </div>
                                                                </div>
                                                                @if ($subpoin->details->isNotEmpty())
                                                                    <div class="space-y-0 ml-6">
                                                                        @foreach ($subpoin->details as $detail)
                                                                            @php
                                                                                // Identifikasi header (Perorangan, Badan Hukum, dll.)
                                                                                $headerPattern =
                                                                                    '/^(Perorangan|Badan Hukum|Badan Usaha)(?:\s*:)?/';
                                                                                $isHeader = preg_match(
                                                                                    $headerPattern,
                                                                                    $detail->text,
                                                                                    $headerMatches,
                                                                                );
                                                                                $headerText = $isHeader
                                                                                    ? $headerMatches[1] . ':'
                                                                                    : null;

                                                                                // Pisahkan item non-header
                                                                                $cleanDetailText = $isHeader
                                                                                    ? preg_replace(
                                                                                        $headerPattern,
                                                                                        '',
                                                                                        trim($detail->text),
                                                                                    )
                                                                                    : $detail->text;
                                                                                $items = array_filter(
                                                                                    array_map(
                                                                                        'trim',
                                                                                        preg_split(
                                                                                            '/(?<!\w\.\s)-(?=\s)/',
                                                                                            $cleanDetailText,
                                                                                        ),
                                                                                    ),
                                                                                    fn($item) => !empty($item),
                                                                                );

                                                                                $detailFormattedItems = [];
                                                                                if ($isHeader) {
                                                                                    $detailFormattedItems[] = [
                                                                                        'text' => $headerText,
                                                                                        'isHeader' => true,
                                                                                        'isNumber' => false,
                                                                                        'isLetter' => false,
                                                                                        'isBullet' => false,
                                                                                    ];
                                                                                }
                                                                                foreach ($items as $item) {
                                                                                    $isNumber = preg_match(
                                                                                        '/^\d+(\.\s|\)\s)/',
                                                                                        $item,
                                                                                    );
                                                                                    $isLetter = preg_match(
                                                                                        '/^[a-zA-Z]\.\s/',
                                                                                        $item,
                                                                                    );
                                                                                    $isBullet = preg_match(
                                                                                        '/^-\s/',
                                                                                        $item,
                                                                                    );

                                                                                    // Pertahankan tanda a. atau - sesuai input, hapus tanda number
                                                                                    $itemText = $isNumber
                                                                                        ? preg_replace(
                                                                                            '/^\d+(\.\s|\)\s)/',
                                                                                            '',
                                                                                            $item,
                                                                                        )
                                                                                        : ($isLetter || $isBullet
                                                                                            ? $item
                                                                                            : "- $item");
                                                                                    $formattedItem = preg_replace(
                                                                                        $urlPattern,
                                                                                        '<a href="$1" class="text-blue-600 dark:text-blue-400 hover:underline font-medium break-all" target="_blank" rel="noopener noreferrer">$1</a>',
                                                                                        $itemText,
                                                                                    );
                                                                                    $detailFormattedItems[] = [
                                                                                        'text' => $formattedItem,
                                                                                        'isHeader' => false,
                                                                                        'isNumber' => $isNumber,
                                                                                        'isLetter' => $isLetter,
                                                                                        'isBullet' =>
                                                                                            $isBullet ||
                                                                                            (!$isNumber && !$isLetter),
                                                                                    ];
                                                                                }
                                                                            @endphp
                                                                            @foreach ($detailFormattedItems as $item)
                                                                                <div
                                                                                    class="flex items-start space-x-3 {{ $item['isHeader'] ? 'ml-4' : 'ml-6' }}">
                                                                                    <div
                                                                                        class="text-gray-700 dark:text-gray-300 leading-relaxed break-words ">
                                                                                        {!! $item['text'] !!}
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @endforeach
                                                                    </div>
                                                                @endif
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
                    <a href="{{ LaravelLocalization::getLocalizedURL(null, route('kbli.index')) }}"
                        class="flex items-center space-x-3 px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>{{ __('messages.back_to_search') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
