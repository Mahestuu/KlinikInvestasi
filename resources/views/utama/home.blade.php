@extends('utama.app')

@section('title', __('messages.home_title'))

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

        <!-- SLIDER UTAMA -->
        <div
            class="swiper mySwiper carouselUtama swiper-container swiper1 w-full h-[300px] lg:h-[55vh] relative mx-auto z-10 overflow-hidden">
            <div class="swiper-wrapper">
                <div class="swiper-slide w-full h-full relative overflow-hidden">
                    <img src="{{ asset('images/bg_banner1.jpg') }}" alt="{{ __('messages.slider_1_title') }}" loading="lazy"
                        class="w-full h-full object-cover absolute top-0 left-0 transition-transform duration-700 ease-out">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent z-5 dark:from-black/90">
                    </div>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                        <h2 class="text-base md:text-2xl lg:text-6xl font-bold drop-shadow-md">
                            {{ __('messages.slider_1_title') }}</h2>
                        <p class="text-xs md:text-sm lg:text-xl lg:mt-3 drop-shadow-md">
                            {{ __('messages.slider_1_description') }}</p>
                    </div>
                </div>
                <div class="swiper-slide w-full h-full relative overflow-hidden">
                    <img src="{{ asset('images/bg_banner2.jpg') }}" alt="{{ __('messages.slider_2_title') }}" loading="lazy"
                        class="w-full h-full object-cover absolute top-0 left-0 transition-transform duration-700 ease-out">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent z-5 dark:from-black/80">
                    </div>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                        <h2 class="text-base md:text-2xl lg:text-6xl font-bold drop-shadow-md">
                            {{ __('messages.slider_2_title') }}</h2>
                        <p class="text-xs md:text-sm lg:text-xl lg:mt-3 drop-shadow-md">
                            {{ __('messages.slider_2_description') }}</p>
                    </div>
                </div>
                <div class="swiper-slide w-full h-full relative overflow-hidden">
                    <img src="{{ asset('images/bg_banner3.jpg') }}" alt="{{ __('messages.slider_3_title') }}" loading="lazy"
                        class="w-full h-full object-cover absolute top-0 left-0 transition-transform duration-700 ease-out">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent z-5 dark:from-black/80">
                    </div>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-center z-10 w-[70%]">
                        <h2 class="text-xl md:text-3xl lg:text-5xl xl:text-6xl font-bold mb-3 lg:mb-5 animate-fade-in-up">
                            {{ __('messages.slider_3_title') }}</h2>
                        <p class="text-sm md:text-base lg:text-xl opacity-90 animate-fade-in-up delay-200">
                            {{ __('messages.slider_3_description') }}</p>
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

    <!-- LAYANAN KAMI SECTION -->
    <section
        class="relative py-12 lg:py-18 bg-gradient-to-b from-white to-blue-50 dark:from-gray-900 dark:to-gray-800 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-20 bg-gradient-to-b from-transparent to-white/80 dark:to-gray-900/80">
        </div>
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center mb-10 lg:mb-16">
                <h2 class="text-2xl lg:text-4xl font-bold text-blue-900 dark:text-white mb-4" data-aos="fade-up">
                    {{ __('messages.services_title') }}</h2>
                <p class="text-gray-600 dark:text-gray-300 text-base lg:text-lg" data-aos="fade-up" data-aos-delay="100">
                    {{ __('messages.services_description') }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-4 lg:p-8 max-w-6xl mx-auto -mt-10 lg:-mt-12 relative z-20 overflow-hidden"
                data-aos="fade-up" data-aos-duration="1000">
                <div class="swiper mySwiper cardSwiper swiper-container swiper2 w-full">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="relative w-full h-64 lg:h-80 rounded-2xl overflow-hidden group">
                                <img src="{{ asset('images/images_3.jpg') }}" alt="{{ __('messages.service_1_title') }}"
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
                                        <h3 class="text-xl lg:text-2xl font-bold">{{ __('messages.service_1_title') }}</h3>
                                    </div>
                                    <p class="text-sm lg:text-base opacity-90">{{ __('messages.service_1_description') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="relative w-full h-64 lg:h-80 rounded-2xl overflow-hidden group">
                                <img src="{{ asset('images/images_2.jpg') }}" alt="{{ __('messages.service_2_title') }}"
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
                                        <h3 class="text-xl lg:text-2xl font-bold">{{ __('messages.service_2_title') }}</h3>
                                    </div>
                                    <p class="text-sm lg:text-base opacity-90">{{ __('messages.service_2_description') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="relative w-full h-64 lg:h-80 rounded-2xl overflow-hidden group">
                                <img src="{{ asset('images/images_1.jpg') }}" alt="{{ __('messages.service_3_title') }}"
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
                                        <h3 class="text-xl lg:text-2xl font-bold">{{ __('messages.service_3_title') }}</h3>
                                    </div>
                                    <p class="text-sm lg:text-base opacity-90">{{ __('messages.service_3_description') }}
                                    </p>
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
                        <img src="{{ asset('images/images_8.png') }}" alt="{{ __('messages.about_title') }}"
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
                    <h2 class="text-2xl lg:text-4xl font-bold text-blue-900 dark:text-white mb-4 lg:mb-6">
                        {{ __('messages.about_title') }}</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-base lg:text-lg mb-6">
                        {{ __('messages.about_description') }}</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('messages.about_feature_1') }}</p>
                        </div>
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('messages.about_feature_2') }}</p>
                        </div>
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('messages.about_feature_3') }}</p>
                        </div>
                        <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-check text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ __('messages.about_feature_4') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEPUTAR INFORMASI -->
    <div
        class="w-full py-12 lg:py-20 bg-gradient-to-br from-blue-800 to-blue-600 dark:from-gray-800 dark:to-gray-700 text-white overflow-hidden">
        <div class="mx-auto px-5 xl:px-28">
            <h2 class="text-center text-2xl lg:text-4xl font-bold mb-4" data-aos="fade-down">
                {{ __('messages.info_title') }}</h2>
            <p class="text-center max-w-2xl mx-auto mb-10 lg:mb-16 opacity-90" data-aos="fade-down" data-aos-delay="200">
                {{ __('messages.info_description') }}</p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="group bg-white/10 dark:bg-white/5 backdrop-blur-sm p-6 rounded-2xl hover:bg-white/20 dark:hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 cursor-pointer"
                    data-aos="fade-up" data-aos-duration="1000">
                    <a href="{{ LaravelLocalization::getLocalizedURL(null, route('kbli.index')) }}">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="bg-yellow-500 p-4 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-book-open fa-3x text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ __('messages.info_kbli_title') }}</h3>
                            <p class="text-sm lg:text-base opacity-90 mb-4">{{ __('messages.info_kbli_description') }}</p>
                            <button class="text-yellow-400 font-semibold text-sm flex items-center mt-auto">
                                {{ __('messages.learn_more') }}
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
                    <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index')) }}">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="bg-blue-400 p-4 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-swatchbook fa-3x text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ __('messages.info_pb_umku_title') }}</h3>
                            <p class="text-sm lg:text-base opacity-90 mb-4">{{ __('messages.info_pb_umku_description') }}
                            </p>
                            <button class="text-yellow-400 font-semibold text-sm flex items-center mt-auto">
                                {{ __('messages.learn_more') }}
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
                    <a href="https://oss.go.id/id/panduan/635970086345c7d71a8144b2">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="bg-green-500 p-4 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-folder-open fa-3x text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">{{ __('messages.info_lkpm_title') }}</h3>
                            <p class="text-sm lg:text-base opacity-90 mb-4">{{ __('messages.info_lkpm_description') }}</p>
                            <button class="text-yellow-400 font-semibold text-sm flex items-center mt-auto">
                                {{ __('messages.learn_more') }}
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
            </div>
        </div>
    </div>

    <!-- TESTIMONI SECTION -->
    <section class="py-12 lg:py-20 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="max-w-4xl mx-auto text-center mb-10 lg:mb-16">
                <h2 class="text-2xl lg:text-4xl font-bold text-blue-900 dark:text-white mb-4" data-aos="fade-down">
                    {{ __('messages.testimonials_title') }}</h2>
                <p class="text-gray-600 dark:text-gray-300 text-base lg:text-lg" data-aos="fade-down"
                    data-aos-delay="100">{{ __('messages.testimonials_description') }}</p>
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
                            <h4 class="font-bold text-blue-900 dark:text-white">{{ __('messages.testimonial_1_name') }}
                            </h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">{{ __('messages.testimonial_1_role') }}
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm lg:text-base">
                        {{ __('messages.testimonial_1_text') }}</p>
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
                            <h4 class="font-bold text-blue-900 dark:text-white">{{ __('messages.testimonial_2_name') }}
                            </h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">{{ __('messages.testimonial_2_role') }}
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm lg:text-base">
                        {{ __('messages.testimonial_2_text') }}</p>
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
                            <h4 class="font-bold text-blue-900 dark:text-white">{{ __('messages.testimonial_3_name') }}
                            </h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300">{{ __('messages.testimonial_3_role') }}
                            </p>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 text-sm lg:text-base">
                        {{ __('messages.testimonial_3_text') }}</p>
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

    <!-- CTA SECTION -->
    <div
        class="w-full py-12 lg:py-20 bg-gradient-to-r from-blue-600 to-blue-800 dark:from-gray-700 dark:to-gray-800 text-white overflow-hidden">
        <div class="mx-auto px-5 xl:px-28 text-center">
            <h2 class="text-2xl lg:text-4xl font-bold mb-4" data-aos="fade-down">{{ __('messages.cta_title') }}</h2>
            <p class="text-lg lg:text-xl max-w-3xl mx-auto mb-8 opacity-90" data-aos="fade-down" data-aos-delay="200">
                {{ __('messages.cta_description') }}</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="300">
                <a href="https://maps.app.goo.gl/Lq1ZudbmVkiysatf9">
                    <button
                        class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-blue-900 font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg cursor-pointer">
                        <i class="fas fa-map-marker-alt text-xl mr-2"></i>{{ __('messages.cta_visit_us') }}
                    </button>
                </a>
            </div>

            <div class="mt-10 flex flex-col sm:flex-row justify-center items-center gap-6 sm:gap-12 max-w-2xl mx-auto"
                data-aos="fade-up" data-aos-delay="500">
                <div class="flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-2xl mr-3 text-yellow-400"></i>
                    <div>
                        <p class="font-semibold">{{ __('messages.cta_location_title') }}</p>
                        <p class="text-sm opacity-80">{!! __('messages.cta_location') !!}</p>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <i class="fas fa-envelope text-2xl mr-3 text-yellow-400"></i>
                    <div>
                        <p class="font-semibold">{{ __('messages.cta_email_title') }}</p>
                        <p class="text-sm opacity-80">{{ __('messages.cta_email') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ SECTION -->
    <div class="w-full py-12 lg:py-20 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="mx-auto px-5 xl:px-28">
            <h2 class="text-center text-2xl lg:text-4xl font-bold mb-4 text-blue-900 dark:text-white"
                data-aos="fade-down">{{ __('messages.faq_title') }}</h2>
            <p class="text-center text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mb-10 lg:mb-16" data-aos="fade-down"
                data-aos-delay="200">{{ __('messages.faq_description') }}</p>

            <div class="max-w-3xl mx-auto" data-aos="fade-up">
                <div class="space-y-4">
                    <div tabindex="0"
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('messages.faq_1_question') }}
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>{{ __('messages.faq_1_answer') }}</p>
                        </div>
                    </div>

                    <div
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('messages.faq_2_question') }}
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>{{ __('messages.faq_2_answer') }}</p>
                        </div>
                    </div>

                    <div
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('messages.faq_3_question') }}
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>{{ __('messages.faq_3_answer') }}</p>
                        </div>
                    </div>

                    <div
                        class="collapse collapse-arrow border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden">
                        <input type="checkbox" class="peer" />
                        <div
                            class="collapse-title p-4 lg:p-6 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-semibold text-gray-800 dark:text-gray-200">
                            {{ __('messages.faq_4_question') }}
                        </div>
                        <div class="collapse-content bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <p>{{ __('messages.faq_4_answer') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
