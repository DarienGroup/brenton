/**
 * Team member gallery — square photo slider with lightbox.
 *
 * Inline view: prev/counter/next step through slides; expand button opens
 * the lightbox. The lightbox renders the full-size image and provides its
 * own prev/counter/next, plus close on the X button, Escape, or click on
 * the backdrop. ArrowLeft / ArrowRight navigate when the lightbox is open.
 *
 * A single-image gallery has no controls — this initializer is a no-op for it.
 */

const BODY_LOCK_CLASS = 'team-member-gallery-open';

function init(root) {
  const slides = Array.from(root.querySelectorAll('[data-team-gallery-slide]'));
  if (slides.length === 0) return;

  const lightbox       = root.querySelector('[data-team-gallery-lightbox]');
  const lightboxImage  = root.querySelector('[data-team-gallery-lightbox-image]');
  const openBtn        = root.querySelector('[data-team-gallery-open]');
  const closeBtn       = root.querySelector('[data-team-gallery-close]');
  const counters       = Array.from(root.querySelectorAll('[data-team-gallery-current]'));
  const prevBtns       = Array.from(root.querySelectorAll('[data-team-gallery-prev]'));
  const nextBtns       = Array.from(root.querySelectorAll('[data-team-gallery-next]'));

  const count = slides.length;
  let index = 0;
  let lightboxOpen = false;
  let lastFocus = null;

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
    if (lightboxOpen && lightboxImage) {
      const active = slides[index].querySelector('img');
      if (active) {
        const full = active.getAttribute('data-full-src') || active.currentSrc || active.src;
        lightboxImage.src = full;
        lightboxImage.alt = active.alt || '';
      }
    }
  };

  const openLightbox = () => {
    if (!lightbox || !lightboxImage) return;
    const active = slides[index].querySelector('img');
    if (active) {
      const full = active.getAttribute('data-full-src') || active.currentSrc || active.src;
      lightboxImage.src = full;
      lightboxImage.alt = active.alt || '';
    }
    lastFocus = document.activeElement;
    lightbox.hidden = false;
    document.body.classList.add(BODY_LOCK_CLASS);
    lightboxOpen = true;
    if (closeBtn) closeBtn.focus();
  };

  const closeLightbox = () => {
    if (!lightbox) return;
    lightbox.hidden = true;
    document.body.classList.remove(BODY_LOCK_CLASS);
    lightboxOpen = false;
    if (lastFocus && typeof lastFocus.focus === 'function') {
      lastFocus.focus();
    }
  };

  prevBtns.forEach((btn) => btn.addEventListener('click', () => showSlide(index - 1)));
  nextBtns.forEach((btn) => btn.addEventListener('click', () => showSlide(index + 1)));

  if (openBtn) openBtn.addEventListener('click', openLightbox);
  if (closeBtn) closeBtn.addEventListener('click', closeLightbox);

  if (lightbox) {
    // Click on the backdrop (but not on the stage / image) closes.
    lightbox.addEventListener('click', (event) => {
      if (event.target === lightbox) {
        closeLightbox();
      }
    });
  }

  document.addEventListener('keydown', (event) => {
    if (!lightboxOpen) return;
    if (event.key === 'Escape') {
      event.preventDefault();
      closeLightbox();
    } else if (event.key === 'ArrowLeft') {
      event.preventDefault();
      showSlide(index - 1);
    } else if (event.key === 'ArrowRight') {
      event.preventDefault();
      showSlide(index + 1);
    }
  });

  updateCounters();
}

export function initTeamMemberGallery() {
  document.querySelectorAll('[data-team-gallery]').forEach(init);
}
