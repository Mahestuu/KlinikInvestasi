    <nav class="navbar bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-2xl sticky top-0 z-50 w-full border-b border-blue-200/20 dark:border-gray-700/30">
        <div class="container mx-auto flex items-center justify-between h-20 px-4 lg:px-8">
            <!-- Brand Logo -->
            <div class="navbar-start">
                <a href="{{ LaravelLocalization::getLocalizedURL(null, route('home')) }}" class="flex items-center space-x-3 group">
                    <div class="flex flex-col">
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-blue-900 dark:from-blue-400 dark:to-blue-200 bg-clip-text text-transparent">
                            Klinik Investasi
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 -mt-1">Surabaya</span>
                    </div>
                </a>
            </div>

            <!-- Right Section - Desktop Menu and Language Toggle -->
            <div class="navbar-end items-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 space-x-1">
                    <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('home')) }}"
                            class="flex justify-center items-center px-6 py-3 rounded-2xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group relative overflow-hidden">
                            <span>{{ __('messages.home') }}</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('kbli.index')) }}"
                            class="flex justify-center items-center px-6 py-3 rounded-2xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group relative overflow-hidden">
                            <span>{{ __('messages.business_requirements') }}</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index')) }}"
                            class="flex justify-center items-center px-6 py-3 rounded-2xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group relative overflow-hidden">
                            <span>{{ __('messages.pbumku_requirements') }}</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                        </a>
                    </li>
                </ul>
                <!-- Language Toggle -->
                <div class="dropdown dropdown-end">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all duration-300">
                        <i class="fas fa-globe w-5 h-5"></i>
                    </div>
                    <ul tabindex="0" class="dropdown-content menu bg-white dark:bg-gray-900 rounded-box z-50 mt-4 w-32 p-2 shadow">
                        <li><a href="{{ LaravelLocalization::getLocalizedURL('id', null, [], true) }}">Indonesia</a></li>
                        <li><a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">English</a></li>
                    </ul>
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="dropdown dropdown-end lg:hidden">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                    <svg class="fill-current w-6 h-6 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M64,384H448V341.33H64Zm0-106.67H448V234.67H64ZM64,128v42.67H448V128Z" />
                    </svg>
                </div>
                <ul tabindex="0" class="dropdown-content menu bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl rounded-2xl z-50 mt-4 w-64 p-4 shadow-2xl border border-gray-200/20 dark:border-gray-700/20 space-y-2">
                    <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('home')) }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group">
                            <i class="fas fa-home text-lg group-hover:scale-110 transition-transform"></i>
                            <span>{{ __('messages.home') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('kbli.index')) }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-green-600 hover:to-green-700 transition-all duration-300 group">
                            <i class="fas fa-file-contract text-lg group-hover:scale-110 transition-transform"></i>
                            <span>{{ __('messages.business_requirements') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ LaravelLocalization::getLocalizedURL(null, route('pbumku.index')) }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-purple-600 hover:to-purple-700 transition-all duration-300 group">
                            <i class="fas fa-tasks text-lg group-hover:scale-110 transition-transform"></i>
                            <span>{{ __('messages.pbumku_requirements') }}</span>
                        </a>
                    </li>
                    <li class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 w-full text-left">
                                <i class="fas fa-globe text-lg"></i>
                                <span>{{ __('messages.language') }}</span>
                            </div>
                            <ul tabindex="0" class="dropdown-content menu bg-white dark:bg-gray-900 rounded-box z-50 mt-2 w-56 p-2 shadow">
                                <li><a href="{{ LaravelLocalization::getLocalizedURL('id', null, [], true) }}">Indonesia</a></li>
                                <li><a href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}">English</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>