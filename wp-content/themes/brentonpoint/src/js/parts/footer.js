const MOBILE_BREAKPOINT = '(max-width: 767.98px)';

function initFooterAccordion() {
  const cols = document.querySelectorAll('[data-footer-col]');
  if (!cols.length) return;

  const mql = window.matchMedia(MOBILE_BREAKPOINT);

  cols.forEach((col) => {
    const toggle = col.querySelector('[data-footer-col-toggle]');
    if (!toggle) return;

    toggle.addEventListener('click', () => {
      if (!mql.matches) return;
      const open = col.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
    });
  });

  const sync = () => {
    cols.forEach((col) => {
      const toggle = col.querySelector('[data-footer-col-toggle]');
      if (mql.matches) {
        // collapsed by default on mobile
        col.classList.remove('is-open');
        toggle?.setAttribute('aria-expanded', 'false');
      } else {
        col.classList.add('is-open');
        toggle?.setAttribute('aria-expanded', 'true');
      }
    });
  };

  sync();
  mql.addEventListener('change', sync);
}

function initBackToTop() {
  const btn = document.querySelector('[data-scroll-top]');
  if (!btn) return;
  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

export function initFooter() {
  initFooterAccordion();
  initBackToTop();
}
