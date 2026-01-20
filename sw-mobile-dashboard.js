// Service Worker for Mobile Dashboard PWA
// Scope: /master/ (registered with explicit scope)
const CACHE_NAME = 'mobile-dashboard-v1';
const STATIC_ASSETS = [
  '/css/bootstrap.min.css',
  '/css/font-awesome.min.css',
  '/css/smartadmin-production-plugins.min.css',
  '/css/smartadmin-production.min.css',
  '/css/smartadmin-skins.min.css',
  '/css/smartadmin-rtl.min.css',
  '/css/demo.min.css',
  '/css/select2.min.css',
  '/js/app.config.js',
  '/js/app.min.js',
  '/js/demo.min.js',
  '/js/select2.min.js',
  '/js/bootstrap/bootstrap.min.js',
  '/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js',
  '/js/notification/SmartNotification.min.js',
  '/js/smartwidgets/jarvis.widget.min.js',
  '/js/plugin/fastclick/fastclick.min.js',
  '/js/libs/jquery-2.1.1.min.js',
  '/js/libs/jquery-ui-1.10.3.min.js',
  '/js/plugin/datatables/jquery.dataTables.min.js',
  '/js/plugin/datatables/dataTables.bootstrap.min.js',
  '/img/logo.png'
];

// Install: pre-cache static assets only (HTML requires auth/session)
self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
      return cache.addAll(STATIC_ASSETS).catch(function () {
        // If any fail (e.g. 404), continue - paths may vary by deployment
        return Promise.resolve();
      });
    })
  );
  self.skipWaiting();
});

// Activate: take control and clean old caches
self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (names) {
      return Promise.all(
        names.filter(function (n) { return n !== CACHE_NAME; }).map(function (n) {
          return caches.delete(n);
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch: cache-first for static assets, network-first for pages and API
self.addEventListener('fetch', function (event) {
  var url = event.request.url;
  var isStatic = /\.(css|js|woff2?|ttf|eot|otf|png|jpg|jpeg|gif|ico|svg)(\?.*)?$/i.test(url) ||
    /\/css\//.test(url) || /\/js\//.test(url) || /\/img\//.test(url) || /\/fonts\//.test(url);

  if (isStatic) {
    event.respondWith(
      caches.match(event.request).then(function (cached) {
        return cached || fetch(event.request).then(function (res) {
          var clone = res.clone();
          if (res.status === 200) {
            caches.open(CACHE_NAME).then(function (c) { c.put(event.request, clone); });
          }
          return res;
        });
      })
    );
    return;
  }

  // Pages and API: network first, fallback to cache
  event.respondWith(
    fetch(event.request).catch(function () {
      return caches.match(event.request);
    })
  );
});
