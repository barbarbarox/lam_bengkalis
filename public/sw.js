// Basic Service Worker for PWA
const CACHE_NAME = 'lam-bengkalis-pwa-v1';
const urlsToCache = [
  '/',
  '/manifest.json',
  '/images/icon-192x192.png',
  '/images/icon-512x512.png',
  '/images/logo-lam.gif'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        // Cache resources, ignoring errors if some are missing
        return Promise.allSettled(
          urlsToCache.map(url => cache.add(url).catch(err => console.log('SW Cache error for', url, err)))
        );
      })
  );
});

self.addEventListener('fetch', event => {
  // Stale-while-revalidate strategy for basic PWA compatibility
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          // Fetch new version in background to update cache
          fetch(event.request).then(res => {
            if (res && res.status === 200) {
              caches.open(CACHE_NAME).then(cache => {
                cache.put(event.request, res.clone());
              });
            }
          }).catch(() => {});
          
          return response;
        }
        
        return fetch(event.request).catch(() => {
            // Optional: return an offline page if one is cached
        });
      })
  );
});

// Clean up old caches
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});
