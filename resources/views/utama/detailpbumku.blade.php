@extends('utama.app')

@section('title', __('messages.detailpbumku_title'))

@section('content')
    <!-- HERO SECTION -->
    <section
        class="relative w-full py-8 lg:py-16 bg-gradient-to-br from-blue-800 to-blue-600 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-black/20 z-0 dark:bg-black/40"></div>
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-white/5 rounded-full blur-3xl">
            </div>
        </div>

        <div class="relative z-10 mx-auto px-5 xl:px-28">
            <!-- Back Button -->
            <div class="mb-8" data-aos="fade-right">
                <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index')) }}"
                    class="inline-flex items-center space-x-3 px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl backdrop-blur-sm transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left"></i>
                    <span class="font-semibold">{{ __('messages.back_to_pbumku_list') }}</span>
                </a>
            </div>

            <!-- Main Header -->
            <div class="text-center lg:text-left" data-aos="fade-up">
                <div class="mb-4">
                    <span
                        class="inline-block px-4 py-2 bg-yellow-500 text-purple-900 rounded-full text-sm font-semibold mb-3">
                        {{ __('messages.pbumku_label') }}
                    </span>
                    <h1 class="text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                        {{ $pbumku->nama }}
                    </h1>
                    <p class="text-xl text-purple-100 max-w-3xl">
                        {{ __('messages.pbumku_description') }}
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" data-aos="fade-up">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-building text-xl"></i>
                            </div>
                            <div>
                                <p class="text-purple-100 text-sm">{{ __('messages.sector_label') }}</p>
                                <p class="font-bold text-lg">{{ $pbumku->dinas->nama ?? __('messages.sector_default') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tags text-xl"></i>
                            </div>
                            <div>
                                <p class="text-blue-100 text-sm">{{ __('messages.kbli_related_label') }}</p>
                                <p class="font-bold text-lg">
                                    {{ $pbumku->kbli->isEmpty() ? __('messages.kbli_related_none') : __($pbumku->kbli->count() == 1 ? 'messages.kbli_related_count' : 'messages.kbli_related_count', ['count' => $pbumku->kbli->count()]) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check-circle text-xl"></i>
                            </div>
                            <div>
                                <p class="text-green-100 text-sm">{{ __('messages.status_label') }}</p>
                                <p class="font-bold text-lg">{{ __('messages.status_available') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-delay="200">
                    <!-- Left Column - Main Content -->
                    <div class="lg:col-span-3">
                        <!-- KBLI Terkait Section -->
                        @if (!$pbumku->kbli->isEmpty())
                            <div
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 lg:p-8 mb-8">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div
                                        class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-tags text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('messages.related_kbli_title') }}</h3>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    @foreach ($pbumku->kbli as $kbli)
                                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('kbli.show', $kbli->slug)) }}"
                                            class="inline-flex items-center space-x-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-xl transition-all duration-300 transform hover:scale-105 group">
                                            <span class="font-mono font-bold">{{ $kbli->kode }}</span>
                                            <i
                                                class="fas fa-external-link-alt text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Persyaratan Section -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 lg:px-8 py-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-clipboard-list text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-white">{{ __('messages.pbumku_requirements_title') }}</h3>
                                        <p class="text-base-200 dark:text-white">
                                            {{ __('messages.pbumku_requirements_description') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 lg:p-8">
                                @if ($pbumku->persyaratanPbumku->isEmpty())
                                    <div class="text-center py-12">
                                        <div
                                            class="w-20 h-20 mx-auto mb-6 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                            <i
                                                class="fas fa-clipboard-check text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                                        </div>
                                        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-3">
                                            {{ __('messages.no_pbumku_requirements_title') }}
                                        </h4>
                                        <p class="text-gray-600 dark:text-gray-300 max-w-md mx-auto">
                                            {{ __('messages.no_pbumku_requirements_description') }}
                                        </p>
                                    </div>
                                @else
                                    <div class="space-y-6">
                                        @foreach ($pbumku->persyaratanPbumku as $index => $persyaratan)
                                            <div
                                                class="group bg-gray-50 dark:bg-gray-700 rounded-xl p-6 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-300 border border-gray-200 dark:border-gray-600">
                                                <div class="flex items-start space-x-4">
                                                    <div class="flex-shrink-0">
                                                        <div
                                                            class="w-12 h-12 bg-purple-500 text-white rounded-xl flex items-center justify-center font-bold text-lg">
                                                            {{ $index + 1 }}
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
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

                    <!-- Right Column - Sidebar -->
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

                <!-- Navigation Footer -->
                <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-between items-center" data-aos="fade-up">
                    <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index')) }}"
                        class="flex items-center space-x-3 px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>{{ __('messages.back_to_pbumku_list') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
