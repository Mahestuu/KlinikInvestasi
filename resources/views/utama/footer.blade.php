<footer class="w-full dark:from-gray-900 dark:to-gray-800 bg-gradient-to-br from-blue-800 to-blue-600">
    <div class="container mx-auto py-15">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 text-white">
            <!-- Brand -->
            <div class="mb-6 md:mb-0 lg:pl-1" data-aos="fade-up">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/dpmptsp_logo.png') }}" alt="{{ __('messages.footer_logo_alt') }}"
                        class="h-12 w-auto object-contain">
                    <a href="{{ LaravelLocalization::getLocalizedURL(null, route('home')) }}" class="text-2xl font-bold">
                        {{ __('messages.footer_brand') }}<span class="text-primary">.</span>
                    </a>
                </div>
                <p class="mt-2 text-base text-white/70">
                    {{ __('messages.footer_description') }}
                </p>
                <div class="mt-5">
                    <h5 class="text-lg font-bold mb-3">{{ __('messages.footer_related_links') }}</h5>
                    <ul class="space-y-2">
                        <li>
                            <a href="https://oss.go.id" class="text-white/70 hover:text-gray-200 underline"
                                target="_blank">
                                {{ __('messages.footer_oss') }}
                            </a>
                        </li>
                        <li>
                            <a href="https://oss.go.id/id//kbli" class="text-white/70 hover:text-gray-200 underline"
                                target="_blank">
                                {{ __('messages.footer_kbli_guide') }}
                            </a>
                        </li>
                        <li>
                            <a href="https://oss.go.id/id//umku"
                                class="text-white/70 hover:text-gray-200 underline" target="_blank">
                                {{ __('messages.footer_pbumku_guide') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Link Terkait -->
            <div class="mb-6 md:mb-0 lg:pl-11" data-aos="fade-up" data-aos-delay="100">
                <h5 class="text-2xl font-bold mb-3">{{ __('messages.footer_contact_hours') }}</h5>
                <ul class="text-white/70 space-y-2">
                    <li>
                        <span>{{ __('messages.footer_hours_monday_thursday') }}</span>
                    </li>
                    <li>
                        <span>{{ __('messages.footer_hours_friday') }}</span>
                    </li>
                    <li>
                        <span>{{ __('messages.footer_hours_saturday') }}</span>
                    </li>
                </ul>
                <div class="mt-4">
                    <p class="text-white/70 mb-3">
                        <i class="fa-solid fa-envelope fa-lg mr-2"></i>{{ __('messages.footer_email') }}
                    </p>
                    <p class="text-white/70">
                        <i class="fa-brands fa-whatsapp fa-xl mr-2"></i>
                        <a href="https://wa.me/6285234982434"
                            class="hover:text-gray-200 underline text-lg font-semibold"
                            target="_blank">{{ __('messages.footer_whatsapp') }}</a>
                    </p>
                </div>
            </div>

            <!-- Alamat dan Kontak -->
            <div data-aos="fade-up" data-aos-delay="200">
                <h5 class="text-2xl font-bold mb-3">{{ __('messages.footer_visit') }}</h5>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.8650217811764!2d112.7352756750469!3d-7.256199092750473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f9929a329cb5%3A0x1f8fa3a29f61fb86!2sKlinik%20Investasi%20Kota%20Surabaya!5e0!3m2!1sid!2sid!4v1758090005028!5m2!1sid!2sid"
                    width="330" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                <p class="text-white/70 mb-2 mt-4">
                    <strong>{{ __('messages.footer_address_label') }}</strong><br>
                    {!! __('messages.footer_address') !!}
                </p>
            </div>
        </div>
    </div>
    <div class="text-center py-3 mt-6 bg-blue-950 dark:bg-gray-950">
        <small class="text-white/70">{{ __('messages.footer_copyright') }}</small>
    </div>
</footer>
