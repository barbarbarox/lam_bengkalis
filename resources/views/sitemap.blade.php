<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  {{-- Halaman Statis --}}
  <url>
    <loc>{{ url('/') }}</loc>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>{{ url('/profil') }}</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>{{ url('/berita') }}</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>{{ url('/galeri') }}</loc>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
  </url>
  <url>
    <loc>{{ url('/kontak') }}</loc>
    <changefreq>yearly</changefreq>
    <priority>0.5</priority>
  </url>

  {{-- Berita Dinamis --}}
  @foreach($beritaList as $berita)
  <url>
    <loc>{{ url('/berita/' . $berita->slug) }}</loc>
    <lastmod>{{ ($berita->updated_at ?? $berita->tanggal_publish)?->toAtomString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  @endforeach

</urlset>
