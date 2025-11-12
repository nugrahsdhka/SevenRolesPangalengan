<div id="app-loader" x-data="loadingComponent()" x-init="init()" x-show="show" aria-live="polite" role="status"
    style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;
         background:rgba(255, 255, 255, 0.24);
         -webkit-backdrop-filter:blur(6px) saturate(180%);
         backdrop-filter:blur(6px) saturate(180%);
         margin:0;padding:0;overflow:hidden;">
    <!-- pusat absolut -->
    <div
        style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
              display:flex;flex-direction:column;align-items:center;justify-content:center;line-height:1;">
        <!-- Wrapper logo + ring -->
        <div style="position:relative; width:{{ $size }}px; height:{{ $size }}px;">

            {{-- RING SPINNER --}}
            @if ($ring)
                @if ($mode === 'quarter')
                    {{-- Cincin dengan gap (quarter spinner) --}}
                    <div
                        style="
            position:absolute; inset:0;
            border-radius:9999px;
            border:{{ $ringThickness }}px solid {{ $ringTrail }};
            border-top-color: {{ $ringColor }};
            animation: spin {{ $speed }} linear infinite;
            box-sizing:border-box;">
                    </div>
                @else
                    {{-- Dual ring: dua cincin kontras berlawanan arah --}}
                    <div
                        style="
            position:absolute; inset:0;
            border-radius:9999px;
            border:{{ $ringThickness }}px solid {{ $ringTrail }};
            border-top-color: {{ $ringColor }};
            animation: spin {{ $speed }} linear infinite;
            box-sizing:border-box;">
                    </div>
                    <div
                        style="
            position:absolute; inset:{{ max(2, $ringThickness) }}px; /* inner ring sedikit lebih kecil */
            border-radius:9999px;
            border:{{ max(2, $ringThickness - 1) }}px solid {{ $ringTrail }};
            border-bottom-color: {{ $ringColor }};
            animation: spin-rev calc({{ $speed }} * 1.2) linear infinite;
            box-sizing:border-box;">
                    </div>
                @endif
            @endif

            {{-- LOGO DI TENGAH --}}
            @if ($img)
                <img src="{{ $img }}" alt="{{ $alt }}" width="{{ $logo }}"
                    height="{{ $logo }}"
                    style="position:absolute;top:50%;left:50%;
                 transform:translate(-50%,-50%);
                 width:{{ $logo }}px;height:{{ $logo }}px;object-fit:contain;">
            @else
                {{-- fallback spinner kalau tidak ada img --}}
                <div
                    style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                    width:{{ min(52, $logo) }}px;height:{{ min(52, $logo) }}px;
                    border:4px solid #E5E7EB;border-top-color:#111827;border-radius:9999px;
                    animation: spin .9s linear infinite;">
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes spin-rev {
            to {
                transform: rotate(-360deg)
            }
        }

        html,
        body {
            overscroll-behavior: none;
        }
    </style>

    <script>
        function loadingComponent() {
            return {
                show: true,
                minMs: 0, // <- TANPA penundaan ekstra
                graceMs: 0, // <- TANPA grace tambahan
                _startedAt: performance.now(),
                _pending: 0,
                _hideTimer: null,

                init() {
                    const hide = () => this._scheduleHide(true);

                    // Freeze scroll saat tampil
                    document.documentElement.style.overflow = 'hidden';
                    document.body.style.overflow = 'hidden';

                    if (document.readyState === 'complete') hide();
                    else window.addEventListener('load', hide, {
                        once: true
                    });

                    // Saat meninggalkan halaman (navigasi) tampilkan loader
                    window.addEventListener('beforeunload', () => {
                        this._pending = 0;
                        this._show();
                    });

                    // Expose API global
                    const self = this;
                    window.AppLoader = {
                        on() {
                            self._pending++;
                            self._show();
                        },
                        off() {
                            self._pending = Math.max(0, self._pending - 1);
                            self._scheduleHide();
                        },
                        forceOn() {
                            self._pending = 0;
                            self._show();
                        },
                        forceOff() {
                            self._pending = 0;
                            self._reallyHide();
                        },
                    };

                    // Intercept fetch agar loader ikut untuk request async
                    const _fetch = window.fetch;
                    window.fetch = async (...args) => {
                        try {
                            AppLoader.on();
                            return await _fetch(...args);
                        } finally {
                            AppLoader.off();
                        }
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
                    wait > 0 ? this._hideTimer = setTimeout(go, wait) : go();
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
