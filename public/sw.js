const CACHE_VERSION = 'v2';
const STATIC_CACHE  = `laranews-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE = `laranews-dynamic-${CACHE_VERSION}`;
const IMAGE_CACHE   = `laranews-images-${CACHE_VERSION}`;
const API_CACHE     = `laranews-api-${CACHE_VERSION}`;

const STATIC_ASSETS = ['/offline', '/manifest.json'];

const CACHE_LIMITS = {
    [DYNAMIC_CACHE]: 50,
    [IMAGE_CACHE]: 60,
    [API_CACHE]: 30,
};

// ─── Install ────────────────────────────────────────────────────────────────
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// ─── Activate ───────────────────────────────────────────────────────────────
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys
                    .filter(k => k.startsWith('laranews-') &&
                        ![STATIC_CACHE, DYNAMIC_CACHE, IMAGE_CACHE, API_CACHE].includes(k))
                    .map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ─── Fetch ──────────────────────────────────────────────────────────────────
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    if (request.method !== 'GET') return;
    if (url.pathname.startsWith('/admin')) return;
    if (url.pathname.startsWith('/horizon')) return;

    if (url.pathname.startsWith('/api/')) {
        event.respondWith(networkFirst(request, API_CACHE));
        return;
    }

    if (request.destination === 'image' || /\.(png|jpg|jpeg|gif|webp|svg|ico)$/i.test(url.pathname)) {
        event.respondWith(cacheFirst(request, IMAGE_CACHE));
        return;
    }

    if (/\.(css|js|woff2?|ttf|otf)$/i.test(url.pathname) || url.pathname.startsWith('/build/')) {
        event.respondWith(cacheFirst(request, STATIC_CACHE));
        return;
    }

    if (request.headers.get('Accept')?.includes('text/html')) {
        event.respondWith(networkFirst(request, DYNAMIC_CACHE));
        return;
    }

    event.respondWith(networkFirst(request, DYNAMIC_CACHE));
});

// ─── Strategies ─────────────────────────────────────────────────────────────
async function cacheFirst(request, cacheName) {
    const cached = await caches.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request);
        if (response.ok) await putInCache(request, response.clone(), cacheName);
        return response;
    } catch {
        return offlineFallback(request);
    }
}

async function networkFirst(request, cacheName) {
    try {
        const response = await fetch(request);
        if (response.ok) await putInCache(request, response.clone(), cacheName);
        return response;
    } catch {
        const cached = await caches.match(request);
        return cached ?? offlineFallback(request);
    }
}

async function putInCache(request, response, cacheName) {
    const cache = await caches.open(cacheName);
    await cache.put(request, response);
    trimCache(cacheName, CACHE_LIMITS[cacheName] ?? 50);
}

async function trimCache(cacheName, max) {
    const cache = await caches.open(cacheName);
    const keys  = await cache.keys();
    if (keys.length > max) {
        await Promise.all(keys.slice(0, keys.length - max).map(k => cache.delete(k)));
    }
}

async function offlineFallback(request) {
    if (request.headers.get('Accept')?.includes('text/html')) {
        const page = await caches.match('/offline');
        if (page) return page;
    }
    return new Response('Offline', { status: 503, statusText: 'Offline' });
}

// ─── Push Notifications ──────────────────────────────────────────────────────
self.addEventListener('push', event => {
    if (!event.data) return;
    let payload;
    try { payload = event.data.json(); } catch { payload = { title: 'Dhivehi News', body: event.data.text() }; }

    event.waitUntil(
        self.registration.showNotification(payload.title ?? 'Dhivehi News', {
            body:     payload.body    ?? '',
            icon:     payload.icon    ?? '/icons/icon-192.png',
            badge:    payload.badge   ?? '/icons/badge-72.png',
            image:    payload.image   ?? undefined,
            data:     { url: payload.url ?? '/' },
            vibrate:  [200, 100, 200],
            tag:      payload.tag ?? 'laranews',
            renotify: true,
        })
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();
    const url = event.notification.data?.url ?? '/';
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(list => {
            const win = list.find(c => c.url === url);
            return win ? win.focus() : clients.openWindow(url);
        })
    );
});

// ─── Background Sync ─────────────────────────────────────────────────────────
self.addEventListener('sync', event => {
    if (event.tag === 'sync-bookmarks') {
        event.waitUntil(syncPendingRequests('pending-bookmarks', '/api/v1/bookmarks'));
    }
});

async function syncPendingRequests(store, endpoint) {
    try {
        const db      = await openIDB();
        const pending = await db.getAll(store);
        for (const item of pending) {
            try {
                const res = await fetch(endpoint, {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${item.token}` },
                    body:    JSON.stringify({ post_id: item.postId }),
                });
                if (res.ok) await db.delete(store, item.id);
            } catch { /* will retry next sync */ }
        }
    } catch { /* IDB not available */ }
}

function openIDB() {
    return new Promise((resolve, reject) => {
        const req = indexedDB.open('laranews-sw', 1);
        req.onupgradeneeded = e => {
            ['pending-bookmarks'].forEach(name => {
                if (!e.target.result.objectStoreNames.contains(name)) {
                    e.target.result.createObjectStore(name, { keyPath: 'id', autoIncrement: true });
                }
            });
        };
        req.onsuccess = e => {
            const db = e.target.result;
            resolve({
                getAll: store => new Promise((res, rej) => {
                    const tx = db.transaction(store, 'readonly');
                    const r  = tx.objectStore(store).getAll();
                    r.onsuccess = () => res(r.result);
                    r.onerror   = () => rej(r.error);
                }),
                delete: (store, id) => new Promise((res, rej) => {
                    const tx = db.transaction(store, 'readwrite');
                    const r  = tx.objectStore(store).delete(id);
                    r.onsuccess = () => res();
                    r.onerror   = () => rej(r.error);
                }),
            });
        };
        req.onerror = () => reject(req.error);
    });
}
