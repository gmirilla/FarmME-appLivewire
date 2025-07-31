const CACHE_NAME = 'farm-cache-v1';
const urlsToCache = [
  '/farm/onboardinglist',
  '/offline.html',
  '/offline-fe',
  '/css/app.css',
  '/js/app.js',
  'https://code.jquery.com/jquery-3.7.1.js',
  'https://cdn.datatables.net/2.2.2/js/dataTables.js',
  'https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js',
  'https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
];

// Install
self.addEventListener("install", event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then(async cache => {
      const urls = [
        '/offline.html',
        '/farm/onboardinglist',
        '/offline-fe',
        '/css/app.css',
        '/js/app.js',
        'https://code.jquery.com/jquery-3.7.1.js',
        'https://cdn.datatables.net/2.2.2/js/dataTables.js',
        'https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js',
        'https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
      ];

      for (const url of urls) {
        try {
          const response = await fetch(url);
          if (response.ok) {
            await cache.put(url, response);
          } else {
            console.warn('Skipped caching', url, 'response not ok');
          }
        } catch (err) {
          console.error('Failed to cache', url, err);
        }
      }
    })
  );
});


// Activate
self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.map(key => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      )
    )
  );
  return self.clients.claim();
});

// Fetch
self.addEventListener("fetch", event => {
  const url = new URL(event.request.url);
  
  if (event.request.mode === "navigate") {
    if (url.pathname === "/farm/onboardinglist") {
      event.respondWith(
        fetch(event.request)
          .then(response => {
            caches.open(CACHE_NAME).then(cache => cache.put(event.request, response.clone()));
            return response;
          })
          .catch(() => caches.match(event.request) || caches.match('/offline.html'))
      );
    }
     else if (url.pathname === "/offline-fe") {
    event.respondWith(
      caches.match('/offline-fe') || caches.match('/offline.html')
    );
  }
 
    else {
      event.respondWith(
        fetch(event.request)
          .catch(() => caches.match('/offline.html'))
      );
    }
  } else {
    event.respondWith(
      caches.match(event.request)
        .then(response => response || fetch(event.request))
    );
  }
});