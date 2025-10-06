<nav
    class="navbar bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-2xl sticky top-0 z-50 w-full border-b border-blue-200/20 dark:border-gray-700/30">
    <div class="container mx-auto flex items-center justify-between h-20 px-4 lg:px-8">
        <!-- Brand Logo -->
        <div class="navbar-start">
            <a href="/" class="flex items-center space-x-3 group">
                <div class="flex flex-col">
                    <span
                        class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-blue-900 dark:from-blue-400 dark:to-blue-200 bg-clip-text text-transparent">
                        Klinik Investasi
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 -mt-1">Surabaya</span>
                </div>
            </a>
        </div>

        <!-- Right Section - Desktop Menu and Dark Mode Toggle -->
        <div class="navbar-end items-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1 space-x-1">
                <li>
                    <a href="/"
                        class="flex justify-center items-center px-6 py-3 rounded-2xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group relative overflow-hidden">
                        <span>Beranda</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/kbli"
                        class="flex justify-center items-center px-6 py-3 rounded-2xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group relative overflow-hidden">
                        <span>Persyaratan Berusaha</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/pbumku"
                        class="flex justify-center items-center px-6 py-3 rounded-2xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group relative overflow-hidden">
                        <span>Persyaratan PB UMKU</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10">
                        </div>
                    </a>
                </li>
            </ul>
            <!-- Dark Mode Toggle -->
            <button id="theme-toggle" class="btn btn-ghost btn-circle hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all duration-300 group">
                <label class="swap swap-rotate w-8 h-8">
                    <input type="checkbox" class="theme-controller" value="synthwave" />
                    <!-- Sun icon (light mode) -->
                    <svg class="swap-off fill-current w-5 h-5 text-yellow-500 group-hover:scale-110 transition-transform"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                            d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
                    </svg>
                    <!-- Moon icon (dark mode) -->
                    <svg class="swap-on fill-current w-5 h-5 text-blue-400 group-hover:scale-110 transition-transform"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path
                            d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
                    </svg>
                </label>
            </button>
        </div>

        <!-- Mobile Menu Toggle -->
        <div class="dropdown dropdown-end lg:hidden">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                <!-- Hamburger Icon -->
                <svg class="fill-current w-6 h-6 text-gray-700 dark:text-gray-300"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M64,384H448V341.33H64Zm0-106.67H448V234.67H64ZM64,128v42.67H448V128Z" />
                </svg>
            </div>

            <ul tabindex="0"
                class="dropdown-content menu bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl rounded-2xl z-50 mt-4 w-64 p-4 shadow-2xl border border-gray-200/20 dark:border-gray-700/20 space-y-2">
                <li>
                    <a href="/"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group">
                        <i class="fas fa-home text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                <li>
                    <a href="/kbli"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group">
                        <i class="fas fa-file-contract text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Persyaratan Berusaha</span>
                    </a>
                </li>
                <li>
                    <a href="/pbumku"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-blue-700 transition-all duration-300 group">
                        <i class="fas fa-tasks text-lg group-hover:scale-110 transition-transform"></i>
                        <span>Persyaratan PB UMKU</span>
                    </a>
                </li>x
                <li class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                    <button onclick="toggleTheme()"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-300 w-full text-left">
                        <i class="fas fa-moon text-lg"></i>
                        <span>Toggle Tema</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>