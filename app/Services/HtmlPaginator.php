<?php

namespace App\Services;

use DOMDocument;
use DOMNode;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class HtmlPaginator
{
    /**
     * Membagi string HTML panjang menjadi array halaman berdasarkan batas karakter (teks).
     *
     * @param string $html Konten HTML
     * @param int $maxLength Batas karakter teks murni per halaman
     * @return array<string> Array berisi HTML untuk setiap halaman
     */
    public static function paginate(string $html, int $maxLength = 3000): array
    {
        if (empty(trim($html))) {
            return [];
        }

        // Hindari masalah encoding dengan tag xml header
        $dom = new DOMDocument();
        // Supress warnings untuk HTML5 tags yang tidak dikenali
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $pages = [];
        $currentHtml = '';
        $currentLength = 0;

        foreach ($dom->childNodes as $node) {
            // Abaikan deklarasi XML
            if ($node->nodeType === XML_PI_NODE && $node->nodeName === 'xml') {
                continue;
            }

            $nodeHtml = $dom->saveHTML($node);
            $nodeText = $node->textContent;
            $nodeLength = mb_strlen(trim($nodeText));

            // Jika menambahkan node ini membuat halaman terlalu panjang (dan halaman tidak kosong)
            // Atau jika ditemukan elemen pagebreak spesifik (jika ada)
            if ($currentLength > 0 && ($currentLength + $nodeLength) > $maxLength) {
                $pages[] = trim($currentHtml);
                $currentHtml = $nodeHtml;
                $currentLength = $nodeLength;
            } else {
                $currentHtml .= $nodeHtml;
                $currentLength += $nodeLength;
            }
        }

        if (trim($currentHtml) !== '') {
            $pages[] = trim($currentHtml);
        }

        // Fallback jika tidak ada halaman
        return count($pages) > 0 ? $pages : [$html];
    }
}
