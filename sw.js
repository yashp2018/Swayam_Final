// Swayam Service Worker - Spiritual Platform PWA
const CACHE_NAME = 'swayam-v1.0.0';
const STATIC_CACHE = 'swayam-static-v1';
const DYNAMIC_CACHE = 'swayam-dynamic-v1';

// Files to cache for offline functionality
const STATIC_FILES = [
  '/',
  '/index.html',
  '/about.html',
  '/blogs.html',
  '/login.html',
  '/dashboard.html',
  '/create-blog.html',
  '/public/css/style.css',
  '/public/css/admin.css',
  '/public/js/main.js',
  '/public/js/auth.js',
  '/public/images/icons/icon-192x192.png',
  '/public/images/icons/icon-512x512.png',
  '/manifest.json'
];

// Spiritual quotes for offline inspiration
const SPIRITUAL_QUOTES = [
  "साधं, सोपं, सरळ जीवन जगण्याची कला - The art of simple living",
  "आत्मानं विद्धि - Know thyself",
  "सर्वे भवन्तु सुखिनः - May all beings be happy",
  "योगः कर्मसु कौशलम् - Yoga is skill in action",
  "अहिंसा परमो धर्मः - Non-violence is the highest virtue"
];

// Install Service Worker
self.addEventListener('install', event => {
  console.log('🕉️ Swayam Service Worker installing...');
  
  event.waitUntil(
    caches.open(STATIC_CACHE)
      .then(cache => {
        console.log('📦 Caching static files for offline spiritual experience');
        return cache.addAll(STATIC_FILES);
      })
      .then(() => {
        console.log('✅ Static files cached successfully');
        return self.skipWaiting();
      })
      .catch(error => {
        console.error('❌ Error caching static files:', error);
      })
  );
});

// Activate Service Worker
self.addEventListener('activate', event => {
  console.log('🌟 Swayam Service Worker activating...');
  
  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
              console.log('🗑️ Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('✅ Service Worker activated successfully');
        return self.clients.claim();
      })
  );
});

// Fetch Strategy - Cache First for static, Network First for dynamic
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }
  
  // Skip external requests
  if (!url.origin.includes(self.location.origin)) {
    return;
  }
  
  // API requests - Network First
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(networkFirstStrategy(request));
    return;
  }
  
  // Static files - Cache First
  if (STATIC_FILES.includes(url.pathname) || 
      url.pathname.includes('.css') || 
      url.pathname.includes('.js') || 
      url.pathname.includes('.png') || 
      url.pathname.includes('.jpg')) {
    event.respondWith(cacheFirstStrategy(request));
    return;
  }
  
  // HTML pages - Stale While Revalidate
  if (url.pathname.endsWith('.html') || url.pathname === '/') {
    event.respondWith(staleWhileRevalidateStrategy(request));
    return;
  }
  
  // Default - Network First
  event.respondWith(networkFirstStrategy(request));
});

// Cache First Strategy (for static assets)
async function cacheFirstStrategy(request) {
  try {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      const cache = await caches.open(DYNAMIC_CACHE);
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch (error) {
    console.log('📱 Serving from cache (offline):', request.url);
    return caches.match(request) || createOfflinePage();
  }
}

// Network First Strategy (for API calls)
async function networkFirstStrategy(request) {
  try {
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      const cache = await caches.open(DYNAMIC_CACHE);
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch (error) {
    console.log('🔌 Network failed, checking cache:', request.url);
    const cachedResponse = await caches.match(request);
    return cachedResponse || createOfflineResponse();
  }
}

// Stale While Revalidate Strategy (for HTML pages)
async function staleWhileRevalidateStrategy(request) {
  const cache = await caches.open(DYNAMIC_CACHE);
  const cachedResponse = await cache.match(request);
  
  const networkResponsePromise = fetch(request).then(response => {
    if (response.ok) {
      cache.put(request, response.clone());
    }
    return response;
  }).catch(() => null);
  
  return cachedResponse || networkResponsePromise || createOfflinePage();
}

// Create offline page
function createOfflinePage() {
  const randomQuote = SPIRITUAL_QUOTES[Math.floor(Math.random() * SPIRITUAL_QUOTES.length)];
  
  const offlineHTML = `
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Offline - Swayam</title>
      <style>
        body {
          font-family: 'Inter', sans-serif;
          background: linear-gradient(135deg, #927397 0%, #D6809C 100%);
          color: white;
          display: flex;
          align-items: center;
          justify-content: center;
          min-height: 100vh;
          margin: 0;
          text-align: center;
          padding: 2rem;
        }
        .offline-container {
          max-width: 500px;
          background: rgba(255,255,255,0.1);
          padding: 3rem;
          border-radius: 20px;
          backdrop-filter: blur(10px);
        }
        .logo {
          font-size: 3rem;
          margin-bottom: 1rem;
        }
        .title {
          font-size: 2rem;
          margin-bottom: 1rem;
          font-family: 'Playfair Display', serif;
        }
        .quote {
          font-style: italic;
          margin: 2rem 0;
          font-size: 1.1rem;
          opacity: 0.9;
        }
        .message {
          margin-bottom: 2rem;
        }
        .retry-btn {
          background: rgba(255,255,255,0.2);
          border: 2px solid white;
          color: white;
          padding: 1rem 2rem;
          border-radius: 50px;
          cursor: pointer;
          font-size: 1rem;
          transition: all 0.3s ease;
        }
        .retry-btn:hover {
          background: white;
          color: #927397;
        }
      </style>
    </head>
    <body>
      <div class="offline-container">
        <div class="logo">🕉️</div>
        <h1 class="title">Swayam</h1>
        <p class="message">You're currently offline, but your spiritual journey continues...</p>
        <div class="quote">"${randomQuote}"</div>
        <p>Please check your internet connection and try again.</p>
        <button class="retry-btn" onclick="window.location.reload()">
          Try Again
        </button>
      </div>
    </body>
    </html>
  `;
  
  return new Response(offlineHTML, {
    headers: { 'Content-Type': 'text/html' }
  });
}

// Create offline API response
function createOfflineResponse() {
  return new Response(JSON.stringify({
    success: false,
    message: 'You are currently offline. Please check your internet connection.',
    offline: true
  }), {
    headers: { 'Content-Type': 'application/json' }
  });
}

// Background Sync for blog submissions
self.addEventListener('sync', event => {
  if (event.tag === 'blog-sync') {
    console.log('🔄 Syncing pending blogs...');
    event.waitUntil(syncPendingBlogs());
  }
});

// Sync pending blogs when online
async function syncPendingBlogs() {
  try {
    // Get pending blogs from IndexedDB (if implemented)
    console.log('📝 Checking for pending blog submissions...');
    // Implementation would sync offline blog submissions
  } catch (error) {
    console.error('❌ Error syncing blogs:', error);
  }
}

// Push notifications for spiritual reminders
self.addEventListener('push', event => {
  if (event.data) {
    const data = event.data.json();
    const options = {
      body: data.body || 'Time for your daily spiritual practice 🧘',
      icon: '/public/images/icons/icon-192x192.png',
      badge: '/public/images/icons/icon-72x72.png',
      tag: 'spiritual-reminder',
      requireInteraction: true,
      actions: [
        {
          action: 'meditate',
          title: 'Start Meditation',
          icon: '/public/images/icons/icon-96x96.png'
        },
        {
          action: 'dismiss',
          title: 'Later',
          icon: '/public/images/icons/icon-96x96.png'
        }
      ]
    };
    
    event.waitUntil(
      self.registration.showNotification('Swayam Reminder 🕉️', options)
    );
  }
});

// Handle notification clicks
self.addEventListener('notificationclick', event => {
  event.notification.close();
  
  if (event.action === 'meditate') {
    event.waitUntil(
      clients.openWindow('/meditation.html')
    );
  } else if (event.action === 'dismiss') {
    // Just close the notification
  } else {
    event.waitUntil(
      clients.openWindow('/')
    );
  }
});

console.log('🕉️ Swayam Service Worker loaded - May your spiritual journey be blessed!');