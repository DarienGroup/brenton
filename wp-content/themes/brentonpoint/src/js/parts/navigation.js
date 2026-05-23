import { qs, on } from './utils';

export function initNavigation() {
  const menuToggle  = qs('.menu-toggle');
  const mobileMenu  = qs('#mobile-menu');
  const mobileOverlay = qs('.mobile-menu-overlay');
  const searchToggle = qs('.search-toggle');
  const searchBar   = qs('#search-bar');
  const searchInput = qs('.search-bar__input');
  const searchClear = qs('.search-bar__clear');

  // ── Mobile menu ──────────────────────────────────────────────────────────

  function openMobileMenu() {
    mobileMenu.classList.add('is-open');
    mobileMenu.setAttribute('aria-hidden', 'false');
    menuToggle.setAttribute('aria-expanded', 'true');
    menuToggle.classList.add('is-open');
    mobileOverlay?.classList.add('is-open');
  }

  function closeMobileMenu() {
    mobileMenu.classList.remove('is-open');
    mobileMenu.setAttribute('aria-hidden', 'true');
    menuToggle.setAttribute('aria-expanded', 'false');
    menuToggle.classList.remove('is-open');
    mobileOverlay?.classList.remove('is-open');
  }

  if (menuToggle && mobileMenu) {
    on(menuToggle, 'click', () => {
      const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
      if (isOpen) {
        closeMobileMenu();
      } else {
        closeSearch();
        openMobileMenu();
      }
    });

    // Close when a nav link is tapped
    on(mobileMenu, 'click', (e) => {
      if (e.target.closest('a')) closeMobileMenu();
    });
  }

  if (mobileOverlay) {
    on(mobileOverlay, 'click', closeMobileMenu);
  }

  // ── Search bar ───────────────────────────────────────────────────────────

  function openSearch() {
    searchBar.classList.add('is-open');
    searchBar.setAttribute('aria-hidden', 'false');
    searchToggle.setAttribute('aria-expanded', 'true');
    searchToggle.classList.add('is-active');
    // Focus input after transition starts
    setTimeout(() => searchInput?.focus(), 50);
  }

  function closeSearch() {
    searchBar.classList.remove('is-open');
    searchBar.setAttribute('aria-hidden', 'true');
    searchToggle.setAttribute('aria-expanded', 'false');
    searchToggle.classList.remove('is-active');
  }

  if (searchToggle && searchBar) {
    on(searchToggle, 'click', () => {
      const isOpen = searchToggle.getAttribute('aria-expanded') === 'true';
      if (isOpen) {
        closeSearch();
      } else {
        closeMobileMenu();
        openSearch();
      }
    });
  }

  if (searchClear && searchInput) {
    on(searchClear, 'click', () => {
      searchInput.value = '';
      searchInput.focus();
    });
  }

  // ── Global close triggers ────────────────────────────────────────────────

  on(document, 'keydown', (e) => {
    if (e.key === 'Escape') {
      closeSearch();
      closeMobileMenu();
    }
  });

  on(document, 'click', (e) => {
    const header = qs('.site-header');
    if (header && !header.contains(e.target)) {
      closeSearch();
      closeMobileMenu();
    }
  });
}
