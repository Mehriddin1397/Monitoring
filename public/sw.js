// Smart-Ijro PWA Service Worker
const CACHE_NAME = 'smart-ijro-v1';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// Push bildirishnomalarini qabul qilish
self.addEventListener('push', (event) => {
    let data = {
        title: 'Smart-Ijro — Yangi xabar',
        body: 'Sizga yangi xabar keldi',
        url: '/admin/chat',
        icon: '/assets/images/logo.svg'
    };

    if (event.data) {
        try {
            data = Object.assign(data, event.data.json());
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: data.icon || '/assets/images/logo.svg',
        badge: '/assets/images/logo.svg',
        vibrate: [200, 100, 200],
        data: {
            url: data.url || '/admin/chat'
        },
        actions: [
            { action: 'open', title: 'Ochish' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Bildirishnomani bosganda chat sahifasini ochish / fokus qilish
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = (event.notification.data && event.notification.data.url) 
        ? event.notification.data.url 
        : '/admin/chat';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes('/admin/chat') && 'focus' in client) {
                    if (targetUrl) {
                        client.navigate(targetUrl);
                    }
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
