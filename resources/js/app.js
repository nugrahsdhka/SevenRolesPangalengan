import './bootstrap';

import Alpine from 'alpinejs';

import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.Chart = Chart;

window.Alpine = Alpine;
Alpine.start();

const toTopBtn = document.getElementById("toTopBtn");
let scrollTimer;

window.addEventListener("scroll", () => {
  toTopBtn.classList.add("hidden");

  clearTimeout(scrollTimer);

  scrollTimer = setTimeout(() => {
    // Cek posisi, misal hanya muncul kalau sudah >100px dari atas
    if (document.documentElement.scrollTop > 100 || document.body.scrollTop > 100) {
      toTopBtn.classList.remove("hidden");
    }
  }, 300);
});

toTopBtn.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});