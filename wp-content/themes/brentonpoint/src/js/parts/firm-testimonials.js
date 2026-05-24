/**
 * Firm page — Testimonials slider.
 *
 * Lightweight transform-based slider. Two slides per view at >=992px,
 * one below. Arrow buttons step by one slide and loop around at the
 * bounds (next from the last slide jumps to the first). Recalculates
 * on resize because slide width is fluid.
 */

const LG_BREAKPOINT = 992;

function slidesPerView() {
  return window.innerWidth >= LG_BREAKPOINT ? 2 : 1;
}

function wrap(value, length) {
  if (length <= 0) return 0;
  return ((value % length) + length) % length;
}

function init(section) {
  const track = section.querySelector('[data-testimonials-track]');
  const prevBtn = section.querySelector('[data-testimonials-prev]');
  const nextBtn = section.querySelector('[data-testimonials-next]');
  if (!track) return;

  const slides = Array.from(track.children);
  if (slides.length === 0) return;

  let index = 0;

  // Number of distinct positions the track can rest at. Looping is by slide,
  // so we wrap across all slides — when index would push the trailing edge
  // past the end we reset to 0 instead of clamping.
  const positionCount = () => Math.max(1, slides.length - slidesPerView() + 1);

  const update = () => {
    const first = slides[0];
    if (!first) return;
    const slideWidth = first.getBoundingClientRect().width;
    const gapPx = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap || '0') || 0;
    const offset = (slideWidth + gapPx) * index;
    track.style.transform = `translate3d(-${offset}px, 0, 0)`;
  };

  const go = (next) => {
    const count = positionCount();
    // Next past the end wraps to 0; prev from 0 wraps to the last position.
    index = wrap(next, count);
    update();
  };

  if (prevBtn) prevBtn.addEventListener('click', () => go(index - 1));
  if (nextBtn) nextBtn.addEventListener('click', () => go(index + 1));

  // Keyboard support when the slider has focus.
  section.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') {
      event.preventDefault();
      go(index - 1);
    } else if (event.key === 'ArrowRight') {
      event.preventDefault();
      go(index + 1);
    }
  });

  // Recompute on resize — slide width is fluid and slides-per-view changes
  // at the lg breakpoint.
  let resizeTimer = null;
  window.addEventListener('resize', () => {
    if (resizeTimer) cancelAnimationFrame(resizeTimer);
    resizeTimer = requestAnimationFrame(() => {
      index = wrap(index, positionCount());
      update();
    });
  });

  // Re-align after a card's "Show more / Show less" toggle changes its width
  // calculation (transform is in px and slide widths are technically stable,
  // but heights change and we want to be defensive).
  section.addEventListener('testimonial:toggle', () => {
    requestAnimationFrame(update);
  });

  update();
}

export function initFirmTestimonials() {
  document.querySelectorAll('.firm-testimonials-section').forEach(init);
}
