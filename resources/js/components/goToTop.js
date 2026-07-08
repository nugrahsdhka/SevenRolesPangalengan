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