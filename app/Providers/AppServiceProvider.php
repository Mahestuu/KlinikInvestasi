<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register helper function untuk linkify
        if (!function_exists('linkify')) {
            /**
             * Deteksi link dalam teks dan ubah menjadi HTML link dengan styling
             * 
             * @param string $text
             * @return string
             */
            function linkify(string $text): string
            {
                if (empty($text)) {
                    return $text;
                }

                // Pattern untuk mendeteksi URL:
                // - http:// atau https:// diikuti domain
                // - www. diikuti domain
                // - Domain dengan ekstensi (.com, .net, .org, dll)
                $urlPattern = '/(?:(?:https?|ftp):\/\/|www\.)[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)/i';

                // Deteksi email
                $emailPattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';

                // Fungsi untuk mengkonversi URL menjadi link
                $text = preg_replace_callback($urlPattern, function ($matches) {
                    $url = $matches[0];

                    // Jika URL tidak dimulai dengan http:// atau https://, tambahkan http://
                    if (!preg_match('/^https?:\/\//i', $url)) {
                        $url = 'http://' . $url;
                    }

                    // DomPDF v3 memiliki keterbatasan serius dengan styling link
                    // Solusi: Gunakan format yang benar-benar sederhana dengan multiple fallback
                    $linkText = htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8');
                    $linkUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

                    // DomPDF v3 memiliki masalah dengan styling link
                    // Pendekatan: Gunakan inline style langsung pada tag <a> dan span
                    // Multiple layer untuk memastikan styling diterapkan
                    $formattedLink = '<a href="' . $linkUrl . '" style="color: blue; text-decoration: underline;"><span style="color: blue; text-decoration: underline;"><u style="color: blue; text-decoration: underline;">' . $linkText . '</u></span></a>';

                    return $formattedLink;
                }, $text);

                // Konversi email menjadi mailto link
                $text = preg_replace_callback($emailPattern, function ($matches) {
                    $email = $matches[0];
                    // DomPDF v3 memiliki keterbatasan serius dengan styling link
                    // Solusi: Gunakan format yang benar-benar sederhana dengan multiple fallback
                    $emailText = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
                    $emailUrl = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

                    // DomPDF v3 memiliki masalah dengan styling link
                    // Pendekatan: Gunakan inline style langsung pada tag <a> dan span
                    $formattedLink = '<a href="mailto:' . $emailUrl . '" style="color: blue; text-decoration: underline;"><span style="color: blue; text-decoration: underline;"><u style="color: blue; text-decoration: underline;">' . $emailText . '</u></span></a>';

                    return $formattedLink;
                }, $text);

                return $text;
            }
        }
    }
}
