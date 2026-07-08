// resources/js/loading.js
(function() {
    'use strict';

    // ===== STATE =====
    let show = true;
    let minMs = 0;
    let graceMs = 0;
    let startedAt = performance.now();
    let pending = 0;
    let hideTimer = null;

    // ===== DOM REFS =====
    const loader = document.getElementById('app-loader');
    if (!loader) return; // safety kalau elemen nggak ada

    // ===== HELPERS =====
    const getUrl = (input) => {
        let raw = '';
        if (typeof input === 'string') raw = input;
        else if (input instanceof URL) raw = input.href;
        else if (input instanceof Request) raw = input.url;
        else if (input && typeof input.url === 'string') raw = input.url;

        try {
            const urlObj = new URL(raw, location.origin);
            return urlObj.pathname;
        } catch {
            if (raw && raw.startsWith('/')) return raw.split('?')[0];
            return raw || '';
        }
    };

    const _show = () => {
        if (!show) {
            show = true;
            startedAt = performance.now();
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
            loader.style.display = ''; // atau classList.add('active')
        }
        clearTimeout(hideTimer);
    };

    const _reallyHide = () => {
        show = false;
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
        loader.style.display = 'none'; // atau classList.remove('active')
    };

    const _scheduleHide = (force = false) => {
        clearTimeout(hideTimer);
        const elapsed = performance.now() - startedAt;
        const wait = Math.max(0, minMs - elapsed);

        const go = () => {
            if (!force && pending > 0) {
                hideTimer = setTimeout(() => _scheduleHide(false), graceMs || 100);
                return;
            }
            hideTimer = setTimeout(() => _reallyHide(), graceMs || 0);
        };

        wait > 0 ? (hideTimer = setTimeout(go, wait)) : go();
    };

    // ===== APP LOADER API =====
    window.AppLoader = {
        on()       { pending++; _show(); },
        off()      { pending = Math.max(0, pending - 1); _scheduleHide(); },
        forceOn()  { pending = 0; _show(); },
        forceOff() { pending = 0; _reallyHide(); },
    };

    // ===== INIT =====
    const init = () => {
        // Show loader initially
        _show();

        // Hide on window load
        const hide = () => _scheduleHide(true);
        if (document.readyState === 'complete') {
            _reallyHide();
        } else {
            window.addEventListener('load', hide, { once: true });
        }

        // Handle bfcache
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) { pending = 0; _reallyHide(); }
        });

        // beforeunload: show loader immediately when clicking links
        window.addEventListener('beforeunload', () => {
            window.AppLoader.forceOn();
        }, { once: false }); // false = setiap navigasi
    };

    // ===== FETCH INTERCEPTOR =====
    const SKIP_LOADER = ['/api/chat'];
    let pendingRequests = 0;

    if (!window.__fetchWrapped) {
        window.__fetchWrapped = true;

        const _fetch = window.fetch;

        window.fetch = async (...args) => {
            const url = getUrl(args[0]).trim();
            const shouldSkip = SKIP_LOADER.some(skip => url.includes(skip));

            if (shouldSkip) {
                return await _fetch(...args);
            }

            pendingRequests++;
            if (pendingRequests === 1) AppLoader.on();

            try {
                return await _fetch(...args);
            } finally {
                pendingRequests--;
                if (pendingRequests === 0) AppLoader.off();
            }
        };
    }

    // Run init
    init();
})();