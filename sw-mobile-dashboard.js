// Service Worker for Mobile Dashboard PWA
// Scope: /master/ (registered with explicit scope)
const CACHE_NAME = 'mobile-dashboard-v3';
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

  // Statement of Account: network first, keep a copy so the statement can be
  // re-opened offline (the page also keeps its own localStorage snapshot).
  if (url.indexOf('mobile_statementofaccount/get_soa') !== -1) {
    event.respondWith(
      fetch(event.request).then(function (res) {
        if (res && res.status === 200) {
          var clone = res.clone();
          caches.open(CACHE_NAME).then(function (c) { c.put(event.request, clone); });
        }
        return res;
      }).catch(function () {
        return caches.match(event.request);
      })
    );
    return;
  }

  // App login: network first, keep a copy so the installed app can still show
  // the mobile login screen when the device is offline.
  if (url.indexOf('/master/app_login') !== -1) {
    event.respondWith(
      fetch(event.request).then(function (res) {
        if (event.request.method === 'GET' && res && res.status === 200) {
          var clone = res.clone();
          caches.open(CACHE_NAME).then(function (c) { c.put(event.request, clone); });
        }
        return res;
      }).catch(function () {
        return caches.match(event.request).then(function (cached) {
          return cached || caches.match('/master/app_login');
        });
      })
    );
    return;
  }

  // Payment: never cache. A stale QR, amount or status could show a code that no
  // longer works or settle the wrong thing, so these always go to the network.
  if (url.indexOf('/master/mobile_payment') !== -1 ||
      url.indexOf('/master/onlinepayment') !== -1 ||
      url.indexOf('/master/paymentportal') !== -1 ||
      url.indexOf('/master/paymongo') !== -1) {
    event.respondWith(
      fetch(event.request).catch(function () {
        return new Response(
          JSON.stringify({ ok: false, offline: true, message: 'You are offline. Online payment needs a connection.' }),
          { status: 503, headers: { 'Content-Type': 'application/json' } }
        );
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
