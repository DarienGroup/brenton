/**
 * Testimonials slider.
 *
 * Lightweight transform-based slider. By default shows two slides per view at
 * >=992px and one below; sections can opt into a fixed slides-per-view count
 * via the `data-testimonials-per-view` attribute (e.g. the sector-focus page
 * shows a single card per view at every breakpoint). Arrow buttons step by
 * one slide and loop around at the bounds. Recalculates on resize because
 * slide width is fluid.
 *
 * Attach by adding `data-testimonials` to the section.
 */

const LG_BREAKPOINT = 992;

function slidesPerView(section) {
  const override = parseInt(section?.dataset?.testimonialsPerView ?? '', 10);
  if (Number.isFinite(override) && override > 0) {
    return override;
  }
  return window.innerWidth >= LG_BREAKPOINT ? 2 : 1;
}

function wrap(value, length) {
  if (length <= 0) return 0;
  return ((value % length) + length) % length;
}

function init(section) {
  const track = section.querySelector('[data-testimonials-track]');
  // A section can declare multiple prev/next button pairs (e.g. one in the
  // left rail for desktop and a stacked row near the slider on mobile).
  const prevBtns = section.querySelectorAll('[data-testimonials-prev]');
  const nextBtns = section.querySelectorAll('[data-testimonials-next]');
  if (!track) return;

  const slides = Array.from(track.children);
  if (slides.length === 0) return;

  // Cards are aligned to the top of the flex row (see _firm-testimonials-
  // section.scss) so an expanded card grows without dragging its siblings.
  // To keep all collapsed cards equal height anyway, measure the tallest
  // natural collapsed card and pin that as min-height on each card.
  // Skipping while any card is expanded prevents the expanded card's full
  // height from becoming the new baseline.
  const cards = slides
    .map((slide) => slide.querySelector('.testimonial-card'))
    .filter(Boolean);

  const equalizeCardHeights = () => {
    if (cards.length === 0) return;
    const anyExpanded = cards.some((card) =>
      card.querySelector('.testimonial-card__quote--expanded')
    );
    if (anyExpanded) return;

    cards.forEach((card) => { card.style.minHeight = ''; });
    const max = cards.reduce(
      (acc, card) => Math.max(acc, card.getBoundingClientRect().height),
      0
    );
    if (max > 0) {
      cards.forEach((card) => { card.style.minHeight = `${max}px`; });
    }
  };

  let index = 0;

  // Number of distinct positions the track can rest at. Looping is by slide,
  // so we wrap across all slides — when index would push the trailing edge
  // past the end we reset to 0 instead of clamping.
  const positionCount = () => Math.max(1, slides.length - slidesPerView(section) + 1);

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

  prevBtns.forEach((btn) => btn.addEventListener('click', () => go(index - 1)));
  nextBtns.forEach((btn) => btn.addEventListener('click', () => go(index + 1)));

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
      equalizeCardHeights();
      update();
    });
  });

  // Re-align after a card's "Show more / Show less" toggle changes its width
  // calculation (transform is in px and slide widths are technically stable,
  // but heights change and we want to be defensive). `equalizeCardHeights`
  // is a no-op while any card is expanded, so toggling one card open does
  // not change the pinned min-height of the others.
  section.addEventListener('testimonial:toggle', () => {
    requestAnimationFrame(() => {
      equalizeCardHeights();
      update();
    });
  });

  // Initial pin happens after the first layout pass so we measure real
  // rendered heights (fonts, fluid type, images for avatars settle by then).
  requestAnimationFrame(equalizeCardHeights);

  update();
}

export function initFirmTestimonials() {
  document.querySelectorAll('[data-testimonials]').forEach(init);
}
