<div id="app-loader" x-data="loadingComponent()" x-init="init()" x-show="show" aria-live="polite" role="status"
    style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;
           background:rgba(255,255,255,0.24);
           -webkit-backdrop-filter:blur(6px) saturate(180%);
           backdrop-filter:blur(6px) saturate(180%);
           margin:0;padding:0;overflow:hidden;">

    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                display:flex;flex-direction:column;align-items:center;gap:20px;">

        <div style="position:relative;width:{{ $size }}px;height:{{ $size }}px;
                    display:flex;align-items:center;justify-content:center;">

            @if ($ring)
                {{-- Ring 1 --}}
                <div style="
                    position:absolute;inset:0;border-radius:9999px;
                    border:{{ $ringThickness }}px solid {{ $ringColor }};
                    animation:ld-pulse 2.2s ease-out infinite 0s;
                    animation-fill-mode:both;
                    box-sizing:border-box;
                    opacity:0;
                "></div>
                {{-- Ring 2 --}}
                <div style="
                    position:absolute;inset:0;border-radius:9999px;
                    border:{{ $ringThickness }}px solid {{ $ringColor }};
                    animation:ld-pulse 2.2s ease-out infinite 0.75s;
                    animation-fill-mode:both;
                    box-sizing:border-box;
                    opacity:0;
                "></div>
                {{-- Ring 3 --}}
                <div style="
                    position:absolute;inset:0;border-radius:9999px;
                    border:{{ $ringThickness }}px solid {{ $ringColor }};
                    animation:ld-pulse 2.2s ease-out infinite 1.5s;
                    animation-fill-mode:both;
                    box-sizing:border-box;
                    opacity:0;
                "></div>
            @endif

            {{-- Logo di tengah, z-index lebih tinggi --}}
            @if ($img)
                <img src="{{ $img }}" alt="{{ $alt }}"
                     style="position:relative;z-index:2;
                            width:{{ $logo }}px;height:{{ $logo }}px;object-fit:contain;">
            @else
                <div style="position:relative;z-index:2;
                            width:{{ min(40,$logo) }}px;height:{{ min(40,$logo) }}px;
                            border:3px solid #E5E7EB;border-top-color:#111827;border-radius:9999px;
                            animation:ld-spin .9s linear infinite;"></div>
            @endif
        </div>

        <span style="font-size:11px;letter-spacing:0.12em;color:#9CA3AF;text-transform:uppercase;
                     animation:ld-blink 2s ease-in-out infinite;">loading</span>
    </div>

    <style>
        @keyframes ld-pulse { 0%{transform:scale(.3);opacity:.85;} 100%{transform:scale(1);opacity:0;} }
        @keyframes ld-spin  { to { transform:rotate(360deg); } }
        @keyframes ld-blink { 0%,100%{opacity:.3;} 50%{opacity:1;} }
        html,body { overscroll-behavior:none; }
    </style>

    <script>
        function loadingComponent() {
            return {
                show: true,
                minMs: 0,
                graceMs: 0,
                _startedAt: performance.now(),
                _pending: 0,
                _hideTimer: null,

                init() {
                    const hide = () => this._scheduleHide(true);
                    if (document.readyState === 'complete') {
                        this._reallyHide();
                    } else {
                        this._show();
                        window.addEventListener('load', hide, { once: true });
                    }
                    window.addEventListener('pageshow', (event) => {
                        if (event.persisted) { this._pending = 0; this._reallyHide(); }
                    });
                    const self = this;
                    window.AppLoader = {
                        on()       { self._pending++; self._show(); },
                        off()      { self._pending = Math.max(0, self._pending - 1); self._scheduleHide(); },
                        forceOn()  { self._pending = 0; self._show(); },
                        forceOff() { self._pending = 0; self._reallyHide(); },
                    };
                    const _fetch = window.fetch;
                    window.fetch = async (...args) => {
                        try { AppLoader.on(); return await _fetch(...args); }
                        finally { AppLoader.off(); }
                    };
                },

                _show() {
                    if (!this.show) {
                        this.show = true;
                        this._startedAt = performance.now();
                        document.documentElement.style.overflow = 'hidden';
                        document.body.style.overflow = 'hidden';
                    }
                    clearTimeout(this._hideTimer);
                },

                _scheduleHide(force = false) {
                    clearTimeout(this._hideTimer);
                    const elapsed = performance.now() - this._startedAt;
                    const wait = Math.max(0, this.minMs - elapsed);
                    const go = () => {
                        if (!force && this._pending > 0) {
                            this._hideTimer = setTimeout(() => this._scheduleHide(false), this.graceMs);
                            return;
                        }
                        this._hideTimer = setTimeout(() => this._reallyHide(), this.graceMs);
                    };
                    wait > 0 ? (this._hideTimer = setTimeout(go, wait)) : go();
                },

                _reallyHide() {
                    this.show = false;
                    document.documentElement.style.overflow = '';
                    document.body.style.overflow = '';
                },
            }
        }
    </script>
</div>