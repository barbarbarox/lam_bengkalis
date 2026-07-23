<?php

namespace App\Services;

use Mews\Purifier\Facades\Purifier;

/**
 * HtmlSanitizer
 *
 * Wrapper di atas mews/purifier untuk membersihkan konten rich text
 * sebelum disimpan ke database. Mencegah XSS dan injeksi HTML berbahaya.
 *
 * Konfigurasi allowlist diatur di config/purifier.php.
 * Gunakan profile 'default' untuk konten umum, 'strict' untuk teks singkat.
 *
 * Contoh penggunaan di Filament Resource:
 *   protected function mutateFormDataBeforeCreate(array $data): array
 *   {
 *       $data['konten'] = HtmlSanitizer::clean($data['konten']);
 *       return $data;
 *   }
 */
class HtmlSanitizer
{
    /**
     * Bersihkan HTML menggunakan profil HTMLPurifier 'default'.
     * Cocok untuk konten artikel panjang (bold, italic, link, list, heading, dst).
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return Purifier::clean($html, [
            'HTML.Allowed' =>
                'h1,h2,h3,h4,h5,h6,' .
                'p[style],' .
                'br,' .
                'strong,em,u,s,del,ins,mark,sub,sup,' .
                'a[href|title|target|rel],' .
                'ul[style|class],ol[type|style|class],li[style|class],' .
                'blockquote,pre,code,' .
                'hr,' .
                'img[src|alt|width|height|style],' .
                'table[style],thead,tbody,tfoot,' .
                'tr[style],th[style|colspan|rowspan],td[style|colspan|rowspan],' .
                'figure,figcaption,' .
                'div[class|style],span[class|style]',
            'CSS.AllowedProperties' =>
                'text-align,margin,padding,width,height,max-width,' .
                'border,border-collapse,border-spacing,' .
                'background-color,color,font-weight,font-style,text-decoration,list-style-type',
            'HTML.SafeIframe'   => true,
            'URI.SafeIframeRegexp' => '%^https://%',
            'AutoFormat.RemoveEmpty' => false,
        ]);
    }

    /**
     * Bersihkan HTML dengan profil ketat — hanya teks dan pemformatan dasar.
     * Cocok untuk excerpt, sambutan singkat, atau textarea dengan HTML terbatas.
     */
    public static function cleanStrict(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return Purifier::clean($html, [
            'HTML.Allowed' => 'p,br,strong,em,u,a[href|title],ul,ol,li',
        ]);
    }

    /**
     * Hilangkan SEMUA tag HTML, kembalikan teks bersih.
     * Cocok untuk excerpt/meta description yang tidak boleh mengandung HTML.
     */
    public static function strip(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return strip_tags(html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    /**
     * Buat excerpt bersih dari konten HTML (strip tags + potong karakter).
     */
    public static function excerpt(?string $html, int $length = 200): string
    {
        $plain = static::strip($html);
        if (mb_strlen($plain) <= $length) {
            return $plain;
        }

        return mb_substr($plain, 0, $length) . '...';
    }
}
