import './bootstrap';

import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);
window.Chart = Chart;

window.Alpine = Alpine;
Alpine.start();

/**
 * Global helper: jika ingin menyalakan loader otomatis setiap fetch,
 * kita pasang interceptor yang mengecek ketersediaan AppLoader.
 * (Aman walau <x-loading /> belum di-render.)
 */
(function attachGlobalFetchInterceptor() {
  const _fetch = window.fetch;
  window.fetch = async (...args) => {
    try {
      if (window.AppLoader?.on) window.AppLoader.on();
      const res = await _fetch(...args);
      return res;
    } finally {
      if (window.AppLoader?.off) window.AppLoader.off();
    }
  };
})();

// resources/js/app.js
window.addEventListener('beforeunload', () => {
  if (window.AppLoader?.forceOn) window.AppLoader.forceOn();
});


/**
 * Tombol "go to top" — aman kalau elemen tidak ada pada halaman tertentu.
 */
const toTopBtn = document.getElementById('toTopBtn');
let scrollTimer;

if (toTopBtn) {
  window.addEventListener('scroll', () => {
    toTopBtn.classList.add('hidden');
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(() => {
      const scrolled =
        document.documentElement.scrollTop > 100 || document.body.scrollTop > 100;
      if (scrolled) toTopBtn.classList.remove('hidden');
    }, 300);
  });

  toTopBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}
