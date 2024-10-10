var CACHE_NAME = 'CACHE_V1';
var cacheUrls = [
    '/img/logo.png',
    '/img/back.png',
    '/fonts/fa-solid-900.woff2',
    '/fonts/fa-brands-400.woff2',
    '/fonts/fa-regular-400.woff2',
    '/fonts/fa-light-300.woff2',
];


self.addEventListener('activate', function () {
    console.log('SW activate');
});

self.addEventListener('install', function (event) {
    console.log('SW install');
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(cacheUrls);
        })
    );
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function (cachedResponse) {
            if (cachedResponse) {
                return cachedResponse;
            }
            return fetch(event.request);
        })
    );
});