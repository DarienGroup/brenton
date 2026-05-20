import { qs } from '../parts/utils';

export function initHomePage() {
  if (!document.body.classList.contains('home')) return;

  // Home page specific JS goes here
  const hero = qs('.hero');
  if (hero) {
    // e.g. parallax, video background init, etc.
  }
}
