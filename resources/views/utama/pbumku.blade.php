@extends('utama.app')

@section('title', __('messages.pbumku_title_top'))

@section('content')
    <!-- HERO SECTION -->
    <section
        class="relative w-full py-14 lg:py-24 bg-gradient-to-br from-blue-800 to-blue-600 dark:from-gray-900 dark:via-gray-800 dark:to-indigo-900 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-black/20 z-0 dark:bg-black/40"></div>
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -right-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-white/5 rounded-full blur-3xl">
            </div>
        </div>

        <div class="relative z-10 mx-auto px-5 xl:px-28 text-white" data-aos="fade-down" data-aos-duration="1000">
            <div class="max-w-8xl">
                <h2
                    class="py-2 text-3xl lg:text-5xl font-bold mb-6 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                    {{ __('messages.pbumku_title') }}
                </h2>
                <p class="text-lg lg:text-xl mb-6 opacity-90 leading-relaxed">
                    {{ __('messages.pbumku_description_1') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="flex items-start space-x-3 p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                        <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-400 mb-2">{{ __('messages.pbumku_types_title') }}</h4>
                            <p class="text-sm opacity-90">{{ __('messages.pbumku_types_description') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                        <div class="w-10 h-10 rounded-full bg-yellow-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-yellow-400 mb-2">{{ __('messages.pbumku_exclusions_title') }}</h4>
                            <p class="text-sm opacity-90">{{ __('messages.pbumku_exclusions_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEARCH & FILTER SECTION -->
    <section
        class="relative w-full py-8 lg:py-12 bg-gradient-to-b from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-transparent to-white/80 dark:to-gray-900/80">
        </div>
        <div class="relative z-10 mx-auto px-5 xl:px-28">
            <div class="max-w-6xl mx-auto" data-aos="fade-up" data-aos-duration="1000">
                <div class="text-center mb-8">
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white mb-3">
                        {{ __('messages.search_pbumku_title') }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        {{ __('messages.search_pbumku_description') }}
                    </p>
                </div>

                <div class="flex flex-col lg:flex-row gap-4 mb-6">
                    <!-- Dinas Filter - WIDER DROPDOWN WITHOUT TRUNCATE -->
                    <div class="dropdown dropdown-bottom w-full lg:w-80">
                        <div tabindex="0" role="button"
                            class="btn btn-outline w-full justify-start bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 h-14">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-building text-blue-500"></i>
                                <span class="text-left break-words max-w-full">
                                    {{ $selectedDinas ? $dinas->find($selectedDinas)->nama ?? __('messages.all_dinas') : __('messages.all_dinas') }}
                                </span>
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="dropdown-content z-50 mt-2 p-2 shadow-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-box w-80 max-h-80 overflow-y-auto">
                            <li class="mb-1">
                                <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index')) }}"
                                    class="flex items-start space-x-3 px-4 py-3 rounded-lg {{ !$selectedDinas ? 'bg-blue-600 text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                    <i class="fas fa-layer-group w-5 mt-0.5 flex-shrink-0"></i>
                                    <span class="break-words">{{ __('messages.all_dinas') }}</span>
                                </a>
                            </li>
                            @foreach ($dinas as $dina)
                                <li class="mb-1">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index', ['dinas_id' => $dina->dinas_id])) }}"
                                        class="flex items-start space-x-3 px-4 py-3 rounded-lg {{ $selectedDinas == $dina->dinas_id ? 'bg-blue-600 text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                        <i class="fas fa-building w-5 mt-0.5 flex-shrink-0"></i>
                                        <span class="break-words text-left">{{ $dina->nama }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Search Form -->
                    <form action="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.search')) }}" method="GET"
                        class="flex-1">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative flex-1">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <!-- Loading Spinner -->
                                <div id="pbumku-search-loading"
                                    class="absolute inset-y-0 right-0 pr-4 items-center pointer-events-none hidden">
                                    <div class="flex items-center h-full">
                                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <input type="text" name="query" id="pbumku-search-input"
                                    placeholder="{{ __('messages.search_placeholder') }}"
                                    class="w-full pl-12 pr-12 py-4 text-base bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-2xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white transition-all duration-300"
                                    value="{{ $query ?? '' }}" autocomplete="off"
                                    data-live-search-url="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.live-search')) }}" />
                                @if ($selectedDinas)
                                    <input type="hidden" name="dinas_id" id="dinas_id" value="{{ $selectedDinas }}">
                                @endif

                                <!-- Suggestions Dropdown -->
                                <div id="pbumku-suggestions"
                                    class="absolute z-50 w-full mt-2 dark:text-white bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl hidden overflow-hidden">
                                    <ul id="pbumku-suggestions-list" class="max-h-60 overflow-y-auto "></ul>
                                </div>
                            </div>

                            <!-- Search Button -->
                            <button type="submit"
                                class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-2xl shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-2 justify-center">
                                <i class="fas fa-search"></i>
                                <span>{{ __('messages.search') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            {{ __('messages.quick_search') }}
                        </span>
                        <button type="button" data-search="izin"
                            class="quick-search-btn text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors">
                            {{ __('messages.permit') }}
                        </button>
                        <button type="button" data-search="sertifikat"
                            class="quick-search-btn text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors">
                            {{ __('messages.certificate') }}
                        </button>
                        <button type="button" data-search="rekomendasi"
                            class="quick-search-btn text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors">
                            {{ __('messages.recommendation') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RESULTS SECTION -->
    <section class="w-full py-8 lg:py-12 bg-white dark:bg-gray-900">
        <div class="mx-auto px-5 xl:px-28">
            <div class="max-w-6xl mx-auto">
                @if ($pbumku->isEmpty())
                    <div class="text-center py-16" data-aos="fade-up">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-search text-yellow-600 dark:text-yellow-400 text-3xl"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">{{ __('messages.no_results') }}
                        </h4>
                        <p class="text-gray-600 dark:text-gray-300 mb-6 max-w-md mx-auto">
                            {{ __('messages.no_results_description', ['query' => $query ?? __('messages.all')]) }}
                            @if ($selectedDinas)
                                {{ __('messages.for_dinas', ['dinas' => $dinas->find($selectedDinas)->nama ?? '']) }}
                            @endif
                        </p>
                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index')) }}"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl transition-colors inline-flex items-center space-x-2">
                            <i class="fas fa-refresh"></i>
                            <span>{{ __('messages.reset_search') }}</span>
                        </a>
                    </div>
                @else
                    <!-- Results Header -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8" data-aos="fade-up">
                        <div>
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white mb-2">
                                {{ __('messages.results_found') }}
                            </h3>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-300">
                                <span
                                    class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full font-semibold">
                                    {{ __('messages.results_count', ['count' => $pbumku->total()]) }}
                                </span>
                                @if ($selectedDinas)
                                    <span class="flex items-center space-x-2">
                                        <i class="fas fa-filter"></i>
                                        <span>{{ __('messages.dinas_filter', ['dinas' => $dinas->find($selectedDinas)->nama ?? '']) }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 lg:mt-0 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <span>{{ __('messages.showing', ['first' => $pbumku->firstItem(), 'last' => $pbumku->lastItem(), 'total' => $pbumku->total()]) }}</span>
                        </div>
                    </div>

                    <!-- Results Grid -->
                    <div class="space-y-6">
                        @foreach ($pbumku as $item)
                            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-gray-100 dark:border-gray-700 overflow-hidden"
                                data-aos="fade-up" data-aos-duration="1300">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 lg:px-8 py-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-file-contract text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-xl lg:text-2xl font-bold text-white">
                                                    {{ $item->nama }}
                                                </h4>
                                                {{-- <p class="text-blue-100 text-sm">
                                                    {{ __('messages.pbumku_code') }}: {{ $item->pbumku_id }}
                                                </p> --}}
                                            </div>
                                        </div>
                                        <div class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            {{ __('messages.available') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 lg:p-8">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                        <!-- KBLI Terkait -->
                                        <div>
                                            <h5 class="font-bold text-gray-800 dark:text-white mb-3 flex items-center">
                                                <i class="fas fa-tags text-blue-500 mr-2"></i>
                                                {{ __('messages.related_kbli') }}
                                            </h5>
                                            @if ($item->kbli->isEmpty())
                                                <p class="text-gray-500 dark:text-gray-400 text-sm">
                                                    {{ __('messages.no_kbli') }}</p>
                                            @else
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($item->kbli as $kbli)
                                                        <span
                                                            class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-lg text-sm font-mono">
                                                            {{ $kbli->kode }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Action Button -->
                                        <div class="flex items-center justify-end">
                                            <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.show', $item->slug)) }}"
                                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-2 group">
                                                <span>{{ __('messages.view_details') }}</span>
                                                <i
                                                    class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($pbumku->hasPages())
                        <div class="mt-12 flex justify-center" data-aos="fade-up">
                            <div class="join shadow-lg">
                                <!-- Previous Button -->
                                <a href="{{ $pbumku->previousPageUrl() ? LaravelLocalization::getLocalizedURL(null, $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->previousPageUrl()) : '#' }}"
                                    class="join-item btn btn-outline btn-primary {{ $pbumku->onFirstPage() ? 'btn-disabled' : '' }} flex items-center space-x-2">
                                    <i class="fas fa-chevron-left"></i>
                                    <span>{{ __('messages.previous') }}</span>
                                </a>

                                <!-- Page Numbers -->
                                @php
                                    $currentPage = $pbumku->currentPage();
                                    $lastPage = $pbumku->lastPage();
                                    $range = 2;
                                    $start = max(1, $currentPage - $range);
                                    $end = min($lastPage, $currentPage + $range);
                                @endphp

                                @if ($start > 1)
                                    <a href="{{ LaravelLocalization::getLocalizedURL(null, $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->url(1)) }}"
                                        class="join-item btn btn-outline btn-primary {{ $currentPage == 1 ? 'btn-active !bg-blue-600 !text-white !border-blue-600' : '' }}">
                                        1
                                    </a>
                                    @if ($start > 2)
                                        <button class="join-item btn btn-outline btn-primary btn-disabled">...</button>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    <a href="{{ LaravelLocalization::getLocalizedURL(null, $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->url($i)) }}"
                                        class="join-item btn btn-outline btn-primary {{ $currentPage == $i ? 'btn-active !bg-blue-600 !text-white !border-blue-600' : '' }}">
                                        {{ $i }}
                                    </a>
                                @endfor

                                @if ($end < $lastPage)
                                    @if ($end < $lastPage - 1)
                                        <button class="join-item btn btn-outline btn-primary btn-disabled">...</button>
                                    @endif
                                    <a href="{{ LaravelLocalization::getLocalizedURL(null, $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->url($lastPage)) }}"
                                        class="join-item btn btn-outline btn-primary {{ $currentPage == $lastPage ? 'btn-active' : '' }}">
                                        {{ $lastPage }}
                                    </a>
                                @endif

                                <!-- Next Button -->
                                <a href="{{ $pbumku->nextPageUrl() ? LaravelLocalization::getLocalizedURL(null, $pbumku->appends(['dinas_id' => $selectedDinas, 'query' => $query])->nextPageUrl()) : '#' }}"
                                    class="join-item btn btn-outline btn-primary {{ $pbumku->hasMorePages() ? '' : 'btn-disabled' }} flex items-center space-x-2">
                                    <span>{{ __('messages.next') }}</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

@endsection
