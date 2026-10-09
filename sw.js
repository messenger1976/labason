// Service Worker for the customer Statement of Account PWA.
// Registered from public_header.php with scope <base>/master/statementofaccount/.
const CACHE_PREFIX = 'statement-of-account-';
const CACHE_NAME = CACHE_PREFIX + 'v4';
const BASE = new URL('./', self.location).href;
const LOGIN_URL = BASE + 'master/statementofaccount/search';

const PRECACHE = [
  'master/statementofaccount/search',
  'css/bootstrap.min.css',
  'css/font-awesome.min.css',
  'css/soa-app.css?v=1',
  'js/soa-app.js?v=1',
  'images/mroxas-logo-report.jpg',
  'img/soa/lwd-seal.png',
  'img/soa/water-hero.webp',
  'img/soa/water-hero-sm.webp'
].map(function (path) { return BASE + path; });

const OFFLINE_HTML =
  '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">' +
  '<meta name="viewport" content="width=device-width, initial-scale=1">' +
  '<meta name="theme-color" content="#063f66"><title>Offline - LWD Statement</title>' +
  '<style>body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;' +
  'background:#063f66;color:#fff;font-family:-apple-system,"Segoe UI",Roboto,sans-serif;text-align:center;padding:1.5rem}' +
  'h1{font-size:1.3rem;margin:0 0 .5rem}p{opacity:.85;margin:0 0 1.25rem}' +
  'button{border:0;border-radius:.6rem;background:#fff;color:#063f66;font-weight:600;padding:.75rem 1.5rem;font-size:1rem}</style>' +
  '</head><body><div><h1>You are offline</h1><p>Connect to the internet to view your Statement of Account.</p>' +
  '<button onclick="location.reload()">Try again</button></div></body></html>';

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE_NAME).then(function (cache) {
      return Promise.all(PRECACHE.map(function (url) {
        return cache.add(new Request(url, { cache: 'reload' })).catch(function () {});
      }));
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (names) {
      // Only remove our own old caches; the staff mobile dashboard keeps its own.
      return Promise.all(names.filter(function (n) {
        return n.indexOf(CACHE_PREFIX) === 0 && n !== CACHE_NAME;
      }).map(function (n) { return caches.delete(n); }));
    }).then(function () {
      // Older builds registered this worker for the whole site; retire that registration.
      if (self.registration.scope === BASE) {
        return self.registration.unregister();
      }
      return self.clients.claim();
    })
  );
});

function isStatic(url) {
  return /\.(css|js|woff2?|ttf|eot|otf|png|jpe?g|gif|ico|svg|webp)$/i.test(url.pathname);
}

self.addEventListener('fetch', function (event) {
  var req = event.request;
  if (req.method !== 'GET') return;

  var url = new URL(req.url);
  if (url.origin !== self.location.origin) return;

  // Statements and payments carry live balances; never serve them from cache.
  if (req.mode === 'navigate') {
    var isLogin = req.url.split('?')[0] === LOGIN_URL;
    event.respondWith(
      fetch(req).then(function (res) {
        if (isLogin && res && res.status === 200) {
          var copy = res.clone();
          caches.open(CACHE_NAME).then(function (c) { c.put(LOGIN_URL, copy); });
        }
        return res;
      }).catch(function () {
        return (isLogin ? caches.match(LOGIN_URL) : Promise.resolve(null)).then(function (cached) {
          return cached || new Response(OFFLINE_HTML, { headers: { 'Content-Type': 'text/html; charset=utf-8' } });
        });
      })
    );
    return;
  }

  if (isStatic(url)) {
    event.respondWith(
      caches.match(req).then(function (cached) {
        var network = fetch(req).then(function (res) {
          if (res && res.status === 200) {
            var copy = res.clone();
            caches.open(CACHE_NAME).then(function (c) { c.put(req, copy); });
          }
          return res;
        }).catch(function () { return cached; });
        return cached || network;
      })
    );
  }
});
