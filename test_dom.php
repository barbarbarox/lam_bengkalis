<?php
$html = '<p>Paragraf 1 yang cukup panjang.</p><h2>Judul 2</h2><p>Paragraf 2.</p><table><tr><td>Data</td></tr></table><p>Paragraf 3</p>';
$dom = new DOMDocument();
@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$pages = [];
$currentHtml = '';
$currentLen = 0;
foreach ($dom->childNodes as $node) {
    echo "Node: " . $node->nodeName . " - " . mb_strlen($node->textContent) . "\n";
    $currentHtml .= $dom->saveHTML($node);
}
echo $currentHtml;
