<?php

namespace App\Helpers;

class TextHelper
{
    /**
     * Deteksi link dalam teks dan ubah menjadi HTML link dengan styling
     * 
     * @param string $text
     * @return string
     */
    public static function linkify(string $text): string
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

            return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" style="color: #0066cc; text-decoration: underline;">' . htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8') . '</a>';
        }, $text);

        // Konversi email menjadi mailto link
        $text = preg_replace_callback($emailPattern, function ($matches) {
            $email = $matches[0];
            return '<a href="mailto:' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '" style="color: #0066cc; text-decoration: underline;">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</a>';
        }, $text);

        return $text;
    }
}
