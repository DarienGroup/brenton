/**
 * Team member gallery — square photo slider with native <dialog> lightbox.
 *
 * Inline view: prev/counter/next step through slides; the expand button opens
 * the lightbox via the shared modal helper. While the lightbox is open, the
 * lightbox renders the full-size image and provides its own prev/counter/next.
 * ArrowLeft / ArrowRight navigate when the lightbox is open.
 *
 * Backdrop click, close-button click, Escape, focus trap, focus restoration,
 * and scroll lock are all owned by `parts/modal.js` + the native <dialog>
 * element — this file only owns gallery-specific state.
 *
 * A single-image gallery has no controls — this initializer is a no-op for it.
 */

import { bindModal, openModal } from './modal';

function init(root) {
  const slides = Array.from(root.querySelectorAll('[data-team-gallery-slide]'));
  if (slides.length === 0) return;

  const lightbox       = root.querySelector('[data-team-gallery-lightbox]');
  const lightboxImage  = root.querySelector('[data-team-gallery-lightbox-image]');
  const openBtn        = root.querySelector('[data-team-gallery-open]');
  const counters       = Array.from(root.querySelectorAll('[data-team-gallery-current]'));
  const prevBtns       = Array.from(root.querySelectorAll('[data-team-gallery-prev]'));
  const nextBtns       = Array.from(root.querySelectorAll('[data-team-gallery-next]'));

  const count = slides.length;
  let index = 0;

  const wrap = (n) => ((n % count) + count) % count;

  const updateCounters = () => {
    const display = String(index + 1);
    counters.forEach((el) => { el.textContent = display; });
  };

  const showSlide = (next) => {
    index = wrap(next);
    slides.forEach((slide, i) => {
      slide.classList.toggle('is-active', i === index);
    });
    updateCounters();
    if (lightbox && lightbox.open && lightboxImage) {
      const active = slides[index].querySelector('img');
      if (active) {
        const full = active.getAttribute('data-full-src') || active.currentSrc || active.src;
        lightboxImage.src = full;
        lightboxImage.alt = active.alt || '';
      }
    }
  };

  prevBtns.forEach((btn) => btn.addEventListener('click', () => showSlide(index - 1)));
  nextBtns.forEach((btn) => btn.addEventListener('click', () => showSlide(index + 1)));

  if (lightbox) {
    bindModal(lightbox);

    if (openBtn) {
      openBtn.addEventListener('click', () => {
        // Sync the lightbox image to the currently-active slide before opening.
        if (lightboxImage) {
          const active = slides[index].querySelector('img');
          if (active) {
            const full = active.getAttribute('data-full-src') || active.currentSrc || active.src;
            lightboxImage.src = full;
            lightboxImage.alt = active.alt || '';
          }
        }
        openModal(lightbox);
      });
    }

    // Arrow keys are gallery-specific; the native <dialog> handles Escape itself.
    document.addEventListener('keydown', (event) => {
      if (!lightbox.open) return;
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        showSlide(index - 1);
      } else if (event.key === 'ArrowRight') {
        event.preventDefault();
        showSlide(index + 1);
      }
    });
  }

  updateCounters();
}

export function initTeamMemberGallery() {
  document.querySelectorAll('[data-team-gallery]').forEach(init);
}
