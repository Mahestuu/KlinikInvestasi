@extends('utama.app')

@section('title', 'Klinik Investasi Surabaya - KBLI')

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
            <div class="max-w-4xl">
                <h2
                    class="text-3xl lg:text-5xl font-bold mb-6 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                    Klasifikasi Baku Lapangan Usaha Indonesia
                </h2>
                <p class="text-lg lg:text-xl mb-8 opacity-90 leading-relaxed">
                    Sistem pengelompokan aktivitas ekonomi yang menghasilkan barang atau jasa di Indonesia,
                    disusun oleh Badan Pusat Statistik (BPS) untuk menyamakan standar definisi, data statistik,
                    dan memudahkan pelaku usaha menentukan jenis bisnis Anda
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="flex items-start space-x-3 p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                        <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-business-time text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-yellow-400 mb-2">Panduan Bisnis</h4>
                            <p class="text-sm opacity-90">Membantu pelaku usaha dalam menentukan kategori bidang usaha yang
                                akan dijalankan</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                        <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-contract text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-400 mb-2">Perizinan Berusaha</h4>
                            <p class="text-sm opacity-90">Menjadi acuan dalam menentukan jenis dan syarat perizinan di
                                sistem OSS</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 p-4 bg-white/10 rounded-2xl backdrop-blur-sm">
                        <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-400 mb-2">Perencanaan Ekonomi</h4>
                            <p class="text-sm opacity-90">Memberikan informasi bagi pemerintah untuk memantau dan
                                merencanakan kebijakan ekonomi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEARCH SECTION -->
    <section
        class="relative w-full py-8 lg:py-12 bg-gradient-to-b from-white to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-transparent to-white/80 dark:to-gray-900/80">
        </div>
        <div class="relative z-10 mx-auto px-5 xl:px-28">
            <div class="max-w-4xl mx-auto" data-aos="fade-up" data-aos-duration="1000">
                <div class="text-center mb-8">
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white mb-3">
                        Temukan Kode KBLI Anda
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Cari klasifikasi usaha Anda dengan mudah dan cepat
                    </p>
                </div>

                <!-- Search Form -->
                <form action="{{ route('kbli.search') }}" method="GET" class="relative">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="query" id="search-input"
                                placeholder="Ketik nama usaha atau kode KBLI... (Contoh: restoran, 47112, dll)"
                                class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-2xl shadow-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white transition-all duration-300"
                                value="{{ $query ?? '' }}" autocomplete="off" />

                            <!-- Suggestions Dropdown -->
                            <div id="suggestions"
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl hidden overflow-hidden">
                                <ul id="suggestions-list" class="max-h-60 overflow-y-auto"></ul>
                            </div>
                        </div>
                        <button type="submit"
                            class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-2xl shadow-lg transition-all duration-300 transform hover:scale-105 flex justify-center items-center space-x-2">
                            <i class="fas fa-search"></i>
                            <span>Cari KBLI</span>
                        </button>
                    </div>
                </form>

                <!-- Quick Search Tips -->
                <div class="mt-6 flex flex-wrap gap-3 justify-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Tips pencarian:
                    </span>
                    <button type="button" data-search="restoran"
                        class="quick-search-btn text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors">
                        restoran
                    </button>
                    <button type="button" data-search="jasa"
                        class="quick-search-btn text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors">
                        jasa
                    </button>
                    <button type="button" data-search="retail"
                        class="quick-search-btn text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors">
                        retail
                    </button>
                    <button type="button" data-search="manufaktur"
                        class="quick-search-btn text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-700 transition-colors">
                        manufaktur
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- RESULTS SECTION - IMPROVED -->
    @if (isset($kbli))
        <section class="py-8 lg:py-16 bg-white dark:bg-gray-900">
            <div class="mx-auto px-5 xl:px-28">
                <div id="results" class="max-w-6xl mx-auto" data-aos="fade-up" data-aos-duration="1000">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white">
                            Hasil Pencarian
                        </h3>
                        <div
                            class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 px-4 py-2 rounded-full font-semibold">
                            Ditemukan {{ $kbli->count() }} hasil
                        </div>
                    </div>

                    @if ($kbli->isEmpty())
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl p-6 text-center"
                            data-aos="zoom-in">
                            <div
                                class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-yellow-800 dark:text-yellow-300 mb-2">Tidak Ditemukan</h4>
                            <p class="text-yellow-700 dark:text-yellow-400">
                                Tidak ditemukan hasil untuk kata kunci: <strong
                                    class="text-yellow-800 dark:text-yellow-200">"{{ $query }}"</strong>
                            </p>
                            <p class="text-sm text-yellow-600 dark:text-yellow-500 mt-2">
                                Coba gunakan kata kunci yang lebih umum atau periksa pengejaan
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" data-aos="fade-up" data-aos-duration="1200">
                            @foreach ($kbli as $item)
                                <div
                                    class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700 rounded-2xl shadow-lg hover:shadow-2xl border border-gray-100 dark:border-gray-600 transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                                    <div class="p-6 lg:p-8">
                                        <!-- Header with Kode -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div
                                                class="bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 px-3 py-1 rounded-full text-sm font-bold">
                                                Kode: {{ $item->kode }}
                                            </div>
                                            <div
                                                class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <i class="fas fa-arrow-right text-white text-sm"></i>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-4 line-clamp-2">
                                            {{ $item->nama }}
                                        </h4>

                                        <div class="space-y-3 mb-6">
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-tag text-blue-500 mr-3 w-4"></i>
                                                <span><strong>Kategori:</strong>
                                                    {{ $item->kategoriKbli->nama ?? 'Tidak Ada Kategori' }}</span>
                                            </div>
                                            <div class="flex items-start text-sm text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-scroll text-green-500 mr-3 w-4 mt-1"></i>
                                                <span><strong>Ruang Lingkup:</strong>
                                                    {{ Str::limit($item->ruang_lingkup ?? 'Tidak Ada Deskripsi', 120) }}</span>
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                                <i class="fas fa-building text-purple-500 mr-3 w-4"></i>
                                                <span><strong>Dinas:</strong>
                                                    {{ $item->dinas->nama ?? 'Tidak Ada Dinas' }}</span>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <div class="card-actions">
                                            <a href="{{ route('kbli.show', $item->kbli_id) }}"
                                                class="btn btn-primary btn-outline w-full group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all duration-300">
                                                <i class="fas fa-eye mr-2"></i>
                                                Lihat Detail Lengkap
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- ALTERNATIVE VERSION WITH SIMPLER COLLAPSE -->
    <section class="w-full py-8 lg:py-16 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-800 dark:to-gray-900">
        <div class="mx-auto px-5 xl:px-28">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12" data-aos="fade-up">
                    <h3 class="text-3xl lg:text-4xl font-bold text-gray-800 dark:text-white mb-4">
                        📚 Semua KBLI per Dinas
                    </h3>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Jelajahi seluruh klasifikasi usaha berdasarkan dinas terkait di Kota Surabaya
                    </p>
                </div>

                <div class="join join-vertical w-full space-y-4" data-aos="fade-up" data-aos-duration="1500">
                    @foreach ($dinas as $dinasItem)
                        <div
                            class="collapse collapse-arrow join-item border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-2xl shadow-lg">
                            <input type="checkbox" name="dinas-accordion" />
                            <div class="collapse-title text-xl font-bold p-6 lg:p-8">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-building text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xl lg:text-2xl font-bold text-gray-800 dark:text-white">
                                            {{ $dinasItem->nama }}
                                        </h4>
                                        <p class="text-gray-600 dark:text-gray-300 text-sm font-normal">
                                            {{ $dinasItem->kbli->count() }} Klasifikasi Usaha
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="collapse-content">
                                <div class="space-y-3 mt-4 max-h-96 overflow-y-auto pr-2 pb-2">
                                    @foreach ($dinasItem->kbli as $kbli)
                                        <div
                                            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-1">
                                                    <span
                                                        class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-sm font-mono">
                                                        {{ $kbli->kode }}
                                                    </span>
                                                    <span class=" text-gray-500 dark:text-gray-400 lg:text-sm">
                                                        {{ $kbli->kategoriKbli->nama ?? 'Umum' }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-800 dark:text-gray-200 font-medium lg:text-lg">
                                                    {{ $kbli->nama }}
                                                </p>
                                            </div>
                                            <a href="{{ route('kbli.show', $kbli->kbli_id) }}"
                                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm flex items-center space-x-2">
                                                <span>Detail</span>
                                                <i class="fas fa-external-link-alt text-xs"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

{{-- @push('scripts')
    <script>
        // Quick search functionality
        document.querySelectorAll('.quick-search-btn').forEach(button => {
            button.addEventListener('click', function() {
                const searchTerm = this.getAttribute('data-search');
                document.getElementById('search-input').value = searchTerm;
                document.querySelector('form').submit();
            });
        });

        // Clear search functionality
        function clearSearch() {
            document.getElementById('search-input').value = '';
            window.location.href = "{{ route('kbli.index') }}";
        }

        // Enhanced search suggestions
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const suggestions = document.getElementById('suggestions');
            const suggestionsList = document.getElementById('suggestions-list');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.trim();

                    if (query.length < 2) {
                        suggestions.classList.add('hidden');
                        return;
                    }

                    // Simulate API call - in real implementation, this would be an AJAX call
                    showLoadingSuggestions();

                    // For demo purposes, we'll simulate a delay
                    setTimeout(() => {
                        // This would be replaced with actual API response
                        const mockSuggestions = [{
                                code: '47112',
                                name: 'Perdagangan Eceran Berbagai Macam Barang'
                            },
                            {
                                code: '56101',
                                name: 'Restoran dan Rumah Makan'
                            },
                            {
                                code: '62011',
                                name: 'Kegiatan Pemrograman Komputer'
                            },
                            {
                                code: '68110',
                                name: 'Real Estat'
                            },
                            {
                                code: '86901',
                                name: 'Rumah Sakit'
                            }
                        ].filter(item =>
                            item.name.toLowerCase().includes(query.toLowerCase()) ||
                            item.code.includes(query)
                        );

                        displaySuggestions(mockSuggestions);
                    }, 300);
                });

                // Hide suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!suggestions.contains(e.target) && e.target !== searchInput) {
                        suggestions.classList.add('hidden');
                    }
                });
            }

            function showLoadingSuggestions() {
                suggestionsList.innerHTML =
                    '<li class="px-4 py-3 text-gray-500 dark:text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Mencari...</li>';
                suggestions.classList.remove('hidden');
            }

            function displaySuggestions(suggestionsData) {
                if (suggestionsData.length === 0) {
                    suggestionsList.innerHTML =
                        '<li class="px-4 py-3 text-gray-500 dark:text-gray-400">Tidak ada saran</li>';
                } else {
                    suggestionsList.innerHTML = suggestionsData.map(item => `
                    <li class="px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0 transition-colors">
                        <div class="font-semibold text-blue-600 dark:text-blue-400">${item.code}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">${item.name}</div>
                    </li>
                `).join('');

                    // Add click event to suggestions
                    suggestionsList.querySelectorAll('li').forEach((li, index) => {
                        li.addEventListener('click', function() {
                            searchInput.value = suggestionsData[index].name;
                            suggestions.classList.add('hidden');
                            document.querySelector('form').submit();
                        });
                    });
                }
                suggestions.classList.remove('hidden');
            }
        });

        // Smooth scrolling for page anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
@endpush --}}
