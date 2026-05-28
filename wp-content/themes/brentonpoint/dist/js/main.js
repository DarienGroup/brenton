/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/pages/home.js"
/*!******************************!*\
  !*** ./src/js/pages/home.js ***!
  \******************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initHomePage: () => (/* binding */ initHomePage)
/* harmony export */ });
/* harmony import */ var _parts_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../parts/utils */ "./src/js/parts/utils.js");

function initHomePage() {
  if (!document.body.classList.contains('home')) return;

  // Home page specific JS goes here
  const hero = (0,_parts_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.hero');
  if (hero) {
    // e.g. parallax, video background init, etc.
  }
}

/***/ },

/***/ "./src/js/pages/page.js"
/*!******************************!*\
  !*** ./src/js/pages/page.js ***!
  \******************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initPage: () => (/* binding */ initPage)
/* harmony export */ });
function initPage() {
  if (!document.body.classList.contains('page')) return;

  // Single page template JS goes here
}

/***/ },

/***/ "./src/js/pages/portfolio.js"
/*!***********************************!*\
  !*** ./src/js/pages/portfolio.js ***!
  \***********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initPortfolioPopups: () => (/* binding */ initPortfolioPopups),
/* harmony export */   initPortfolioTabs: () => (/* binding */ initPortfolioTabs)
/* harmony export */ });
/* harmony import */ var _parts_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../parts/utils */ "./src/js/parts/utils.js");
/* harmony import */ var _parts_modal__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../parts/modal */ "./src/js/parts/modal.js");


function initPortfolioTabs() {
  const buttons = (0,_parts_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('.portfolio-tabs button');
  const articles = (0,_parts_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('.portfolio_block article');
  const emptyBlocks = (0,_parts_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('.portfolio-section__empty');
  if (!buttons.length) return;

  // Show/hide the per-filter empty-state block. The grid itself stays mounted
  // so the existing layout stays intact — only the empty-state sibling toggles.
  const applyEmptyState = (filter, visibleCount) => {
    emptyBlocks.forEach(block => {
      const matches = block.dataset.emptyFilter === filter;
      block.hidden = !(matches && visibleCount === 0);
    });
  };
  buttons.forEach(button => {
    button.addEventListener('click', () => {
      const block = button.getAttribute('data-block');
      buttons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      let visibleCount = 0;
      articles.forEach(article => {
        let visible;
        if (block === 'All') {
          visible = true;
        } else if (block === 'Active') {
          visible = article.classList.contains('category_portfolio-active');
        } else if (block === 'Realized') {
          visible = article.classList.contains('category_portfolio-realized');
        } else {
          visible = false;
        }
        article.classList.toggle('hide', !visible);
        if (visible) visibleCount++;
      });
      applyEmptyState(block, visibleCount);
    });
  });
}

// Portfolio detail popup. Each card embeds a sibling <dialog>; the shared
// modal helper handles backdrop click + close-button wiring, and the document
// delegate below pairs each open trigger with its matching dialog.
function initPortfolioPopups() {
  const dialogs = (0,_parts_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('.portfolio-popup');
  if (!dialogs.length) return;
  dialogs.forEach(dialog => (0,_parts_modal__WEBPACK_IMPORTED_MODULE_1__.bindModal)(dialog));
  document.addEventListener('click', event => {
    const opener = event.target.closest('[data-portfolio-popup-open]');
    if (!opener) return;
    const card = opener.closest('[data-portfolio-card]');
    const dialog = card && card.querySelector('.portfolio-popup');
    if (dialog) {
      event.preventDefault();
      (0,_parts_modal__WEBPACK_IMPORTED_MODULE_1__.openModal)(dialog);
    }
  });
}

/***/ },

/***/ "./src/js/parts/about-tabs.js"
/*!************************************!*\
  !*** ./src/js/parts/about-tabs.js ***!
  \************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initAboutTabs: () => (/* binding */ initAboutTabs)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./src/js/parts/utils.js");

function initAboutTabs() {
  const roots = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('[data-about-tabs]');
  if (!roots.length) return;
  roots.forEach(root => {
    const tabs = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('[data-about-tab]', root);
    const panels = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('[data-about-panel]', root);
    const labels = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('[data-about-label]', root);
    const labelsEl = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.about-tabs__badge-labels', root);
    if (!tabs.length || !panels.length) return;
    let current = tabs.find(t => t.classList.contains('is-active'))?.dataset.aboutTab || tabs[0].dataset.aboutTab;

    // Measure each label's natural width via an offscreen clone. The live
    // labels can't be measured directly: non-active ones sit at position
    // absolute with inset:0, so their offsetWidth reports the container's
    // width, not the text's. Cloning into the same parent inherits all
    // styling (font, weight, perspective context) for an accurate width.
    const widths = new Map();
    const measureWidths = () => {
      if (!labelsEl) return;
      labels.forEach(label => {
        const clone = label.cloneNode(true);
        clone.style.position = 'static';
        clone.style.visibility = 'hidden';
        clone.style.transform = 'none';
        clone.style.opacity = '1';
        clone.style.pointerEvents = 'none';
        labelsEl.appendChild(clone);
        widths.set(label.dataset.aboutLabel, clone.offsetWidth);
        clone.remove();
      });
    };
    const applyWidth = key => {
      if (!labelsEl) return;
      const w = widths.get(key);
      if (typeof w === 'number') {
        labelsEl.style.width = `${w}px`;
      }
    };
    const activate = key => {
      if (key === current) return;
      root.dataset.aboutActive = key;
      tabs.forEach(tab => {
        const isActive = tab.dataset.aboutTab === key;
        tab.classList.toggle('is-active', isActive);
        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
        tab.setAttribute('tabindex', isActive ? '0' : '-1');
      });
      panels.forEach(panel => {
        const isActive = panel.dataset.aboutPanel === key;
        panel.classList.toggle('is-active', isActive);
        if (isActive) {
          panel.removeAttribute('aria-hidden');
        } else {
          panel.setAttribute('aria-hidden', 'true');
        }
      });

      // Flip-card label: the outgoing label rotates down (+90deg) via
      // .is-leaving so the new label's upward rotation reads as a
      // continuous odometer flip rather than a cross-fade.
      labels.forEach(label => {
        const key2 = label.dataset.aboutLabel;
        const wasActive = key2 === current;
        const isActive = key2 === key;
        label.classList.remove('is-leaving');
        if (isActive) {
          label.classList.add('is-active');
          label.setAttribute('aria-hidden', 'false');
        } else {
          label.classList.remove('is-active');
          label.setAttribute('aria-hidden', 'true');
          if (wasActive) {
            // Force a reflow before applying .is-leaving so the
            // transition starts from the active (0deg) state.
            // eslint-disable-next-line no-unused-expressions
            label.offsetWidth;
            label.classList.add('is-leaving');
          }
        }
      });
      applyWidth(key);
      current = key;
    };

    // Initial measurement + width apply. Re-measure once webfonts settle
    // since pre-font metrics can be a few px off.
    measureWidths();
    applyWidth(current);
    root.dataset.aboutActive = current;
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(() => {
        measureWidths();
        applyWidth(current);
      });
    }
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        activate(tab.dataset.aboutTab);
      });
      tab.addEventListener('keydown', e => {
        if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
        e.preventDefault();
        const idx = tabs.indexOf(tab);
        const next = e.key === 'ArrowRight' ? tabs[(idx + 1) % tabs.length] : tabs[(idx - 1 + tabs.length) % tabs.length];
        next.focus();
        activate(next.dataset.aboutTab);
      });
    });
  });
}

/***/ },

/***/ "./src/js/parts/animations.js"
/*!************************************!*\
  !*** ./src/js/parts/animations.js ***!
  \************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initScrollReveal: () => (/* binding */ initScrollReveal)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./src/js/parts/utils.js");

function initScrollReveal() {
  const els = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('[data-reveal]');
  if (!els.length || !('IntersectionObserver' in window)) return;
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15
  });
  els.forEach(el => observer.observe(el));
}

/***/ },

/***/ "./src/js/parts/contact-form.js"
/*!**************************************!*\
  !*** ./src/js/parts/contact-form.js ***!
  \**************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initContactForm: () => (/* binding */ initContactForm)
/* harmony export */ });
const CAPTCHA_LOAD_TIMEOUT_MS = 4000;
const SELECTORS = {
  section: '.contact-section',
  field: '.ginput_container input, .ginput_container textarea',
  textarea: '.gfield--type-textarea, .gfield:has(textarea)',
  legend: '.gform_required_legend',
  captcha: '.gfield--type-captcha, .gfield--type-recaptcha, .gfield_captcha_container'
};
function injectPlaceholders(section) {
  section.querySelectorAll(SELECTORS.field).forEach(el => {
    if (!el.getAttribute('placeholder')) {
      el.setAttribute('placeholder', ' ');
    }
  });
}
function relocateRequiredLegend(section) {
  const legend = section.querySelector(SELECTORS.legend);
  const textareaField = section.querySelector(SELECTORS.textarea);
  if (!legend || !textareaField) return;
  if (textareaField.nextSibling === legend) return;
  textareaField.parentNode.insertBefore(legend, textareaField.nextSibling);
}
function watchCaptchaLoad(section) {
  const captchaField = section.querySelector(SELECTORS.captcha);
  if (!captchaField) return;
  window.setTimeout(() => {
    const loaded = typeof window.grecaptcha !== 'undefined';
    captchaField.classList.toggle('is-captcha-unloaded', !loaded);
  }, CAPTCHA_LOAD_TIMEOUT_MS);
}
function polishContactForm(root = document) {
  root.querySelectorAll(SELECTORS.section).forEach(section => {
    injectPlaceholders(section);
    relocateRequiredLegend(section);
    watchCaptchaLoad(section);
  });
}
function initContactForm() {
  polishContactForm();
  if (typeof window.jQuery === 'function') {
    window.jQuery(document).on('gform_post_render', () => polishContactForm());
  }
}

/***/ },

/***/ "./src/js/parts/firm-testimonials.js"
/*!*******************************************!*\
  !*** ./src/js/parts/firm-testimonials.js ***!
  \*******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initFirmTestimonials: () => (/* binding */ initFirmTestimonials)
/* harmony export */ });
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
  return (value % length + length) % length;
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
  const cards = slides.map(slide => slide.querySelector('.testimonial-card')).filter(Boolean);
  const equalizeCardHeights = () => {
    if (cards.length === 0) return;
    const anyExpanded = cards.some(card => card.querySelector('.testimonial-card__quote--expanded'));
    if (anyExpanded) return;
    cards.forEach(card => {
      card.style.minHeight = '';
    });
    const max = cards.reduce((acc, card) => Math.max(acc, card.getBoundingClientRect().height), 0);
    if (max > 0) {
      cards.forEach(card => {
        card.style.minHeight = `${max}px`;
      });
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
  const go = next => {
    const count = positionCount();
    // Next past the end wraps to 0; prev from 0 wraps to the last position.
    index = wrap(next, count);
    update();
  };
  prevBtns.forEach(btn => btn.addEventListener('click', () => go(index - 1)));
  nextBtns.forEach(btn => btn.addEventListener('click', () => go(index + 1)));

  // Keyboard support when the slider has focus.
  section.addEventListener('keydown', event => {
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
function initFirmTestimonials() {
  document.querySelectorAll('[data-testimonials]').forEach(init);
}

/***/ },

/***/ "./src/js/parts/footer.js"
/*!********************************!*\
  !*** ./src/js/parts/footer.js ***!
  \********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initFooter: () => (/* binding */ initFooter)
/* harmony export */ });
const MOBILE_BREAKPOINT = '(max-width: 767.98px)';
function initFooterAccordion() {
  const cols = document.querySelectorAll('[data-footer-col]');
  if (!cols.length) return;
  const mql = window.matchMedia(MOBILE_BREAKPOINT);
  cols.forEach(col => {
    const toggle = col.querySelector('[data-footer-col-toggle]');
    if (!toggle) return;
    toggle.addEventListener('click', () => {
      if (!mql.matches) return;
      const open = col.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(open));
    });
  });
  const sync = () => {
    cols.forEach(col => {
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
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
}
function initFooter() {
  initFooterAccordion();
  initBackToTop();
}

/***/ },

/***/ "./src/js/parts/hear-from-us.js"
/*!**************************************!*\
  !*** ./src/js/parts/hear-from-us.js ***!
  \**************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initHearFromUs: () => (/* binding */ initHearFromUs)
/* harmony export */ });
/**
 * "Hear From Us" tiles — click-to-play.
 *
 * On click we replace the cover/play overlay with the actual player:
 *   - youtube : an autoplaying YouTube iframe (using youtube-nocookie domain)
 *   - upload  : a native <video controls autoplay>
 *
 * Only the clicked tile is swapped; other tiles keep their cover state.
 */

function buildYouTubeFrame(id) {
  const iframe = document.createElement('iframe');
  iframe.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(id)}?autoplay=1&rel=0&modestbranding=1&playsinline=1`;
  iframe.title = 'YouTube video player';
  iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
  iframe.allowFullscreen = true;
  iframe.loading = 'eager';
  return iframe;
}
function buildVideoElement(url, mime) {
  const video = document.createElement('video');
  video.controls = true;
  video.autoplay = true;
  video.playsInline = true;
  video.preload = 'auto';
  const source = document.createElement('source');
  source.src = url;
  source.type = mime || 'video/mp4';
  video.appendChild(source);
  return video;
}
function activate(button) {
  if (button.dataset.activated === '1') return;
  button.dataset.activated = '1';
  const card = button.closest('.hear-from-us-card') || button;
  const type = button.dataset.videoType;
  let player = null;
  if (type === 'youtube') {
    const id = button.dataset.youtubeId;
    if (!id) return;
    player = buildYouTubeFrame(id);
  } else if (type === 'upload') {
    const url = button.dataset.videoUrl;
    if (!url) return;
    player = buildVideoElement(url, button.dataset.videoMime);
  }
  if (!player) return;
  const media = document.createElement('div');
  media.className = 'hear-from-us-card__media';
  media.appendChild(player);
  button.appendChild(media);
  card.classList.add('is-playing');
  if (player.tagName === 'VIDEO') {
    player.play().catch(() => {});
  }
}
function initHearFromUs() {
  const buttons = document.querySelectorAll('[data-hear-from-us]');
  buttons.forEach(button => {
    button.addEventListener('click', () => activate(button));
  });
}

/***/ },

/***/ "./src/js/parts/investment-video.js"
/*!******************************************!*\
  !*** ./src/js/parts/investment-video.js ***!
  \******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initInvestmentVideo: () => (/* binding */ initInvestmentVideo)
/* harmony export */ });
/**
 * Investment-criteria background video.
 *
 * Tries the Vimeo iframe first. If Vimeo doesn't start playing within
 * READY_TIMEOUT_MS (slow CDN, blocked embed, ad-blocker, etc.), the iframe is
 * removed and an mp4 <video> fallback is injected from `data-mp4-url`. The mp4
 * is never requested on the happy path — it only enters the DOM on failure.
 */

const VIMEO_SDK_URL = 'https://player.vimeo.com/api/player.js';
const READY_TIMEOUT_MS = 6000;
let sdkPromise = null;
function loadVimeoSdk() {
  if (window.Vimeo && window.Vimeo.Player) {
    return Promise.resolve(window.Vimeo.Player);
  }
  if (sdkPromise) return sdkPromise;
  sdkPromise = new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = VIMEO_SDK_URL;
    script.async = true;
    script.onload = () => {
      if (window.Vimeo && window.Vimeo.Player) {
        resolve(window.Vimeo.Player);
      } else {
        reject(new Error('Vimeo SDK loaded but Player missing'));
      }
    };
    script.onerror = () => reject(new Error('Vimeo SDK failed to load'));
    document.head.appendChild(script);
  });
  return sdkPromise;
}
function injectFallback(section, iframe) {
  const mp4Url = section.dataset.mp4Url;
  const mp4Mime = section.dataset.mp4Mime || 'video/mp4';
  const poster = section.dataset.posterUrl || '';
  const posterAlt = section.dataset.posterAlt || '';
  iframe.remove();
  if (mp4Url) {
    const video = document.createElement('video');
    video.className = 'investment-section__video';
    video.autoplay = true;
    video.muted = true;
    video.loop = true;
    video.playsInline = true;
    video.preload = 'auto';
    if (poster) video.poster = poster;
    const source = document.createElement('source');
    source.src = mp4Url;
    source.type = mp4Mime;
    video.appendChild(source);
    const media = section.querySelector('.investment-section__media');
    if (media) media.appendChild(video);
    video.addEventListener('playing', () => {
      section.classList.add('is-video-playing');
    }, {
      once: true
    });
    video.play().catch(() => {});
  } else if (poster) {
    const img = document.createElement('img');
    img.className = 'investment-section__video';
    img.src = poster;
    img.alt = posterAlt;
    const media = section.querySelector('.investment-section__media');
    if (media) media.insertBefore(img, media.firstChild);
  }
}
function initOne(section) {
  const iframe = section.querySelector('.investment-section__iframe');

  // Server-side <video> path (no Vimeo URL): just fade it in once it plays.
  if (!iframe) {
    const video = section.querySelector('.investment-section__video');
    if (video && video.tagName === 'VIDEO') {
      video.addEventListener('playing', () => {
        section.classList.add('is-video-playing');
      }, {
        once: true
      });
    } else {
      // No video at all — keep the poster visible.
      section.classList.add('is-video-playing');
    }
    return;
  }
  let resolved = false;
  const timeoutId = setTimeout(() => {
    if (resolved) return;
    resolved = true;
    injectFallback(section, iframe);
  }, READY_TIMEOUT_MS);
  loadVimeoSdk().then(Player => {
    if (resolved) return;
    const player = new Player(iframe);
    player.on('playing', () => {
      if (resolved) return;
      resolved = true;
      clearTimeout(timeoutId);
      section.classList.add('is-video-playing');
    });

    // ready() resolving doesn't guarantee playback — autoplay may still be
    // blocked. We try play() explicitly and fall back on failure.
    player.ready().then(() => player.play()).catch(() => {
      if (resolved) return;
      resolved = true;
      clearTimeout(timeoutId);
      injectFallback(section, iframe);
    });
  }).catch(() => {
    if (resolved) return;
    resolved = true;
    clearTimeout(timeoutId);
    injectFallback(section, iframe);
  });
}
function initInvestmentVideo() {
  const sections = document.querySelectorAll('[data-investment-video]');
  sections.forEach(initOne);
}

/***/ },

/***/ "./src/js/parts/modal.js"
/*!*******************************!*\
  !*** ./src/js/parts/modal.js ***!
  \*******************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   bindModal: () => (/* binding */ bindModal),
/* harmony export */   openModal: () => (/* binding */ openModal)
/* harmony export */ });
/**
 * Shared modal helpers — used by every popup in the theme.
 *
 * The theme standardises on the native <dialog> element opened via
 * .showModal(). The browser then provides — for free —
 *   • focus trap inside the dialog while it's open,
 *   • Escape key closes,
 *   • focus restoration to the trigger on close,
 *   • top-layer z-index handling,
 *   • a styleable ::backdrop pseudo-element.
 *
 * What's NOT free is the convention each popup wants:
 *   • clicking the dim backdrop should close the modal,
 *   • close buttons (X icons) should close the modal,
 *   • body scroll should freeze while the modal is open.
 *
 * `bindModal(dialog)` wires up the first two; the third is a single
 * `html:has(dialog[open])` CSS rule that covers every dialog at once.
 *
 * Callers stay responsible for *opening* the dialog (so each feature can run
 * its own pre-open logic, e.g. preparing slide state), and for any keyboard
 * shortcuts beyond Escape (e.g. ArrowLeft / ArrowRight in the gallery).
 */

/**
 * Wire common close behaviour for a dialog.
 *
 * Returns the dialog unchanged so calls can chain.
 *
 * @param {HTMLDialogElement} dialog
 * @param {object} [options]
 * @param {string} [options.closeSelector="[data-modal-close]"]
 *        Selector matched against descendants of the dialog. Every element
 *        matching it gets a click → dialog.close() listener. Defaults to the
 *        shared `[data-modal-close]` attribute — features can pass their own
 *        attribute when they want to keep existing markup.
 */
function bindModal(dialog, options = {}) {
  if (!dialog || typeof dialog.close !== 'function') return dialog;
  const closeSelector = options.closeSelector || '[data-modal-close]';

  // Click on the dialog element itself = click on the backdrop, because all
  // visible content lives inside a child wrapper. (If a caller renders content
  // as a direct child of <dialog>, the click target will be that child and
  // this listener won't fire.)
  dialog.addEventListener('click', event => {
    if (event.target === dialog) {
      dialog.close();
    }
  });
  dialog.querySelectorAll(closeSelector).forEach(btn => {
    btn.addEventListener('click', () => dialog.close());
  });
  return dialog;
}

/**
 * Convenience wrapper: dialog.showModal() with the SSR-safety guard.
 *
 * @param {HTMLDialogElement} dialog
 */
function openModal(dialog) {
  if (dialog && typeof dialog.showModal === 'function' && !dialog.open) {
    dialog.showModal();
  }
}

/***/ },

/***/ "./src/js/parts/navigation.js"
/*!************************************!*\
  !*** ./src/js/parts/navigation.js ***!
  \************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initNavigation: () => (/* binding */ initNavigation)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./src/js/parts/utils.js");

function initNavigation() {
  const menuToggle = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.menu-toggle');
  const mobileMenu = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('#mobile-menu');
  const mobileOverlay = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.mobile-menu-overlay');
  const searchToggle = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.search-toggle');
  const searchBar = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('#search-bar');
  const searchInput = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.search-bar__input');
  const searchClear = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.search-bar__clear');

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
    (0,_utils__WEBPACK_IMPORTED_MODULE_0__.on)(menuToggle, 'click', () => {
      const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
      if (isOpen) {
        closeMobileMenu();
      } else {
        closeSearch();
        openMobileMenu();
      }
    });

    // Close when a nav link is tapped
    (0,_utils__WEBPACK_IMPORTED_MODULE_0__.on)(mobileMenu, 'click', e => {
      if (e.target.closest('a')) closeMobileMenu();
    });
  }
  if (mobileOverlay) {
    (0,_utils__WEBPACK_IMPORTED_MODULE_0__.on)(mobileOverlay, 'click', closeMobileMenu);
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
    (0,_utils__WEBPACK_IMPORTED_MODULE_0__.on)(searchToggle, 'click', () => {
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
    (0,_utils__WEBPACK_IMPORTED_MODULE_0__.on)(searchClear, 'click', () => {
      searchInput.value = '';
      searchInput.focus();
    });
  }

  // ── Global close triggers ────────────────────────────────────────────────

  (0,_utils__WEBPACK_IMPORTED_MODULE_0__.on)(document, 'keydown', e => {
    if (e.key === 'Escape') {
      closeSearch();
      closeMobileMenu();
    }
  });
  (0,_utils__WEBPACK_IMPORTED_MODULE_0__.on)(document, 'click', e => {
    const header = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qs)('.site-header');
    if (header && !header.contains(e.target)) {
      closeSearch();
      closeMobileMenu();
    }
  });
}

/***/ },

/***/ "./src/js/parts/read-more.js"
/*!***********************************!*\
  !*** ./src/js/parts/read-more.js ***!
  \***********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initPortfolioReadMore: () => (/* binding */ initPortfolioReadMore),
/* harmony export */   initTeamReadMore: () => (/* binding */ initTeamReadMore)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./src/js/parts/utils.js");

function initReadMore(blockSelector, maxChars) {
  const blocks = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)(blockSelector);
  blocks.forEach(block => {
    const textBlock = block.querySelector('.entry-content');
    if (!textBlock) return;
    const totalChars = Array.from(textBlock.querySelectorAll('p')).reduce((sum, p) => sum + p.textContent.length, 0);
    if (totalChars <= maxChars) return;
    const btn = document.createElement('button');
    btn.textContent = 'Read More';
    btn.className = 'read-more-btn show';
    btn.addEventListener('click', () => {
      const expanded = textBlock.classList.toggle('active');
      btn.textContent = expanded ? 'Read Less' : 'Read More';
    });
    textBlock.after(btn);
  });
}
function initPortfolioReadMore() {
  initReadMore('.portfolio_block article', 417);
}
function initTeamReadMore() {
  initReadMore('.team_block article', 20000);
}

/***/ },

/***/ "./src/js/parts/team-member-gallery.js"
/*!*********************************************!*\
  !*** ./src/js/parts/team-member-gallery.js ***!
  \*********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initTeamMemberGallery: () => (/* binding */ initTeamMemberGallery)
/* harmony export */ });
/* harmony import */ var _modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modal */ "./src/js/parts/modal.js");
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


function init(root) {
  const slides = Array.from(root.querySelectorAll('[data-team-gallery-slide]'));
  if (slides.length === 0) return;
  const lightbox = root.querySelector('[data-team-gallery-lightbox]');
  const lightboxImage = root.querySelector('[data-team-gallery-lightbox-image]');
  const openBtn = root.querySelector('[data-team-gallery-open]');
  const counters = Array.from(root.querySelectorAll('[data-team-gallery-current]'));
  const prevBtns = Array.from(root.querySelectorAll('[data-team-gallery-prev]'));
  const nextBtns = Array.from(root.querySelectorAll('[data-team-gallery-next]'));
  const count = slides.length;
  let index = 0;
  const wrap = n => (n % count + count) % count;
  const updateCounters = () => {
    const display = String(index + 1);
    counters.forEach(el => {
      el.textContent = display;
    });
  };
  const showSlide = next => {
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
  prevBtns.forEach(btn => btn.addEventListener('click', () => showSlide(index - 1)));
  nextBtns.forEach(btn => btn.addEventListener('click', () => showSlide(index + 1)));
  if (lightbox) {
    (0,_modal__WEBPACK_IMPORTED_MODULE_0__.bindModal)(lightbox);
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
        (0,_modal__WEBPACK_IMPORTED_MODULE_0__.openModal)(lightbox);
      });
    }

    // Arrow keys are gallery-specific; the native <dialog> handles Escape itself.
    document.addEventListener('keydown', event => {
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
function initTeamMemberGallery() {
  document.querySelectorAll('[data-team-gallery]').forEach(init);
}

/***/ },

/***/ "./src/js/parts/testimonial-read-more.js"
/*!***********************************************!*\
  !*** ./src/js/parts/testimonial-read-more.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   initTestimonialReadMore: () => (/* binding */ initTestimonialReadMore)
/* harmony export */ });
/**
 * Testimonial quotes — inline "Show more / Show less" toggle.
 *
 * Quotes longer than [data-truncate-chars] are clipped at the nearest word
 * boundary at or before that threshold. Clicking the inline toggle reveals
 * the rest in place; clicking again collapses it.
 */

const TRUNCATED_CLASS = 'testimonial-card__quote--truncated';
const EXPANDED_CLASS = 'testimonial-card__quote--expanded';
function chevronSvg() {
  // Down chevron — flipped via CSS when expanded.
  return '<svg class="testimonial-card__toggle-icon" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true">' + '<path d="M3 5l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' + '</svg>';
}
function findCut(text, maxChars) {
  if (text.length <= maxChars) return -1;

  // Prefer cutting at the last whitespace at or before the threshold so we
  // don't slice through a word. Fall back to a hard cut if no whitespace.
  const slice = text.slice(0, maxChars);
  const lastWhitespace = Math.max(slice.lastIndexOf(' '), slice.lastIndexOf('\n'));
  return lastWhitespace > 0 ? lastWhitespace : maxChars;
}
function init(quote) {
  if (quote.dataset.truncateReady === '1') return;
  quote.dataset.truncateReady = '1';
  const maxChars = parseInt(quote.dataset.truncateChars, 10);
  if (!maxChars || Number.isNaN(maxChars)) return;
  const fullText = quote.textContent.trim();
  const cut = findCut(fullText, maxChars);
  if (cut < 0) return; // short enough — leave the text alone.

  const visible = fullText.slice(0, cut).replace(/\s+$/, '');
  const rest = fullText.slice(cut).replace(/^\s+/, '');
  if (!rest) return;
  quote.textContent = '';
  quote.classList.add(TRUNCATED_CLASS);
  const visibleNode = document.createTextNode(visible + ' ');
  const restSpan = document.createElement('span');
  restSpan.className = 'testimonial-card__quote-rest';
  restSpan.hidden = true;
  restSpan.textContent = rest;
  const toggle = document.createElement('button');
  toggle.type = 'button';
  toggle.className = 'testimonial-card__toggle';
  toggle.setAttribute('aria-expanded', 'false');
  toggle.innerHTML = `<span class="testimonial-card__toggle-label">Show more</span>${chevronSvg()}`;
  toggle.addEventListener('click', () => {
    const expanded = quote.classList.toggle(EXPANDED_CLASS);
    quote.classList.toggle(TRUNCATED_CLASS, !expanded);
    restSpan.hidden = !expanded;
    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    toggle.querySelector('.testimonial-card__toggle-label').textContent = expanded ? 'Show less' : 'Show more';

    // Tell the slider its slide heights changed so it can re-measure.
    quote.dispatchEvent(new CustomEvent('testimonial:toggle', {
      bubbles: true
    }));
  });
  quote.appendChild(visibleNode);
  quote.appendChild(restSpan);
  quote.appendChild(document.createTextNode(' '));
  quote.appendChild(toggle);
}
function initTestimonialReadMore() {
  document.querySelectorAll('.testimonial-card__quote[data-truncate-chars]').forEach(init);
}

/***/ },

/***/ "./src/js/parts/utils.js"
/*!*******************************!*\
  !*** ./src/js/parts/utils.js ***!
  \*******************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   on: () => (/* binding */ on),
/* harmony export */   qs: () => (/* binding */ qs),
/* harmony export */   qsa: () => (/* binding */ qsa),
/* harmony export */   ready: () => (/* binding */ ready)
/* harmony export */ });
function ready(fn) {
  if (document.readyState !== 'loading') {
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}
function qs(selector, ctx = document) {
  return ctx.querySelector(selector);
}
function qsa(selector, ctx = document) {
  return Array.from(ctx.querySelectorAll(selector));
}
function on(el, event, handler, options = {}) {
  if (!el) return;
  el.addEventListener(event, handler, options);
}

/***/ },

/***/ "./src/scss/main.scss"
/*!****************************!*\
  !*** ./src/scss/main.scss ***!
  \****************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!************************!*\
  !*** ./src/js/main.js ***!
  \************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/main.scss */ "./src/scss/main.scss");
/* harmony import */ var _parts_utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./parts/utils */ "./src/js/parts/utils.js");
/* harmony import */ var _parts_navigation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./parts/navigation */ "./src/js/parts/navigation.js");
/* harmony import */ var _parts_footer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./parts/footer */ "./src/js/parts/footer.js");
/* harmony import */ var _parts_animations__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./parts/animations */ "./src/js/parts/animations.js");
/* harmony import */ var _parts_read_more__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./parts/read-more */ "./src/js/parts/read-more.js");
/* harmony import */ var _parts_contact_form__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./parts/contact-form */ "./src/js/parts/contact-form.js");
/* harmony import */ var _parts_about_tabs__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./parts/about-tabs */ "./src/js/parts/about-tabs.js");
/* harmony import */ var _parts_investment_video__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./parts/investment-video */ "./src/js/parts/investment-video.js");
/* harmony import */ var _parts_hear_from_us__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./parts/hear-from-us */ "./src/js/parts/hear-from-us.js");
/* harmony import */ var _parts_firm_testimonials__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./parts/firm-testimonials */ "./src/js/parts/firm-testimonials.js");
/* harmony import */ var _parts_testimonial_read_more__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./parts/testimonial-read-more */ "./src/js/parts/testimonial-read-more.js");
/* harmony import */ var _parts_team_member_gallery__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./parts/team-member-gallery */ "./src/js/parts/team-member-gallery.js");
/* harmony import */ var _pages_home__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./pages/home */ "./src/js/pages/home.js");
/* harmony import */ var _pages_page__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./pages/page */ "./src/js/pages/page.js");
/* harmony import */ var _pages_portfolio__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./pages/portfolio */ "./src/js/pages/portfolio.js");
















(0,_parts_utils__WEBPACK_IMPORTED_MODULE_1__.ready)(() => {
  (0,_parts_navigation__WEBPACK_IMPORTED_MODULE_2__.initNavigation)();
  (0,_parts_footer__WEBPACK_IMPORTED_MODULE_3__.initFooter)();
  (0,_parts_animations__WEBPACK_IMPORTED_MODULE_4__.initScrollReveal)();
  (0,_parts_read_more__WEBPACK_IMPORTED_MODULE_5__.initPortfolioReadMore)();
  (0,_parts_read_more__WEBPACK_IMPORTED_MODULE_5__.initTeamReadMore)();
  (0,_parts_contact_form__WEBPACK_IMPORTED_MODULE_6__.initContactForm)();
  (0,_parts_about_tabs__WEBPACK_IMPORTED_MODULE_7__.initAboutTabs)();
  (0,_parts_investment_video__WEBPACK_IMPORTED_MODULE_8__.initInvestmentVideo)();
  (0,_parts_hear_from_us__WEBPACK_IMPORTED_MODULE_9__.initHearFromUs)();
  (0,_parts_testimonial_read_more__WEBPACK_IMPORTED_MODULE_11__.initTestimonialReadMore)();
  (0,_parts_firm_testimonials__WEBPACK_IMPORTED_MODULE_10__.initFirmTestimonials)();
  (0,_parts_team_member_gallery__WEBPACK_IMPORTED_MODULE_12__.initTeamMemberGallery)();
  (0,_pages_portfolio__WEBPACK_IMPORTED_MODULE_15__.initPortfolioTabs)();
  (0,_pages_portfolio__WEBPACK_IMPORTED_MODULE_15__.initPortfolioPopups)();
  (0,_pages_home__WEBPACK_IMPORTED_MODULE_13__.initHomePage)();
  (0,_pages_page__WEBPACK_IMPORTED_MODULE_14__.initPage)();
});
})();

/******/ })()
;
//# sourceMappingURL=main.js.map