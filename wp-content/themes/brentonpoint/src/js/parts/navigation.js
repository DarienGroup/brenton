import { qs, on } from './utils';

export function initNavigation() {
  const toggle = qs('.menu-toggle');
  const menu   = qs('#primary-menu');

  if (!toggle || !menu) return;

  on(toggle, 'click', () => {
    const expanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!expanded));
    menu.classList.toggle('is-open', !expanded);
  });

  // Close menu when clicking outside
  on(document, 'click', (e) => {
    if (!toggle.contains(e.target) && !menu.contains(e.target)) {
      toggle.setAttribute('aria-expanded', 'false');
      menu.classList.remove('is-open');
    }
  });
}
