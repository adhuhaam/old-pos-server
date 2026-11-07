const CACHE = 'pos-v87';
const ASSETS = [
  './offline.html',
  './manifest.webmanifest',
  './icon-192.png',
  './icon-512.png',
  './icon-maskable-512.png',
  './apple-touch-icon.png',
  './pwa.js',
  './admin/style.css?v=31',
  './admin/mobile.css?v=7',
  './admin/qrcode.js?v=1',
  './customer/app.css?v=1',
  './customer/app.js?v=2',
  './waiter/app.css?v=59',
  './waiter/app.js?v=78',
  './waiter/fonts/NotoSansBengali-Regular.ttf',
  './waiter/fonts/NotoSansBengali-Bold.ttf',
];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE).then((cache) => cache.addAll(ASSETS)));
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter((key) => key !== CACHE).map((key) => caches.delete(key))))
  );
  self.clients.claim();
});

function isApi(url) {
  return url.pathname.indexOf('/api/') !== -1;
}

function isStatic(pathname) {
  return /\.(?:css|js|png|ttf|webmanifest|html)$/.test(pathname);
}

async function fromNetwork(request) {
  const response = await fetch(request);
  if (response && response.ok && isStatic(new URL(request.url).pathname)) {
    const copy = response.clone();
    caches.open(CACHE).then((cache) => cache.put(request, copy));
  }
  return response;
}

self.addEventListener('fetch', (event) => {
  const request = event.request;
  if (request.method !== 'GET') {
    return;
  }
  const url = new URL(request.url);
  if (url.origin !== self.location.origin || isApi(url)) {
    return;
  }

  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request).catch(() => caches.match(request).then((cached) => cached || caches.match('./offline.html')))
    );
    return;
  }

  if (!isStatic(url.pathname)) {
    return;
  }

  event.respondWith(
    fromNetwork(request).catch(() => caches.match(request).then((cached) => cached || Response.error()))
  );
});
