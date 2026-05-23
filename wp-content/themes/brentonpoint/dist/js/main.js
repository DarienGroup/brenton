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
/* harmony export */   initPortfolioTabs: () => (/* binding */ initPortfolioTabs)
/* harmony export */ });
/* harmony import */ var _parts_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../parts/utils */ "./src/js/parts/utils.js");

function initPortfolioTabs() {
  const buttons = (0,_parts_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('.portfolio-tabs button');
  const articles = (0,_parts_utils__WEBPACK_IMPORTED_MODULE_0__.qsa)('.portfolio_block article');
  if (!buttons.length) return;
  buttons.forEach(button => {
    button.addEventListener('click', () => {
      const block = button.getAttribute('data-block');

      // Update active state on buttons
      buttons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');

      // Show / hide articles
      articles.forEach(article => {
        if (block === 'All') {
          article.classList.remove('hide');
          return;
        }
        const isActive = block === 'Active' && article.classList.contains('category_portfolio-active');
        const isRealized = block === 'Realized' && article.classList.contains('category_portfolio-realized');
        article.classList.toggle('hide', !isActive && !isRealized);
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
/* harmony import */ var _parts_animations__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./parts/animations */ "./src/js/parts/animations.js");
/* harmony import */ var _parts_read_more__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./parts/read-more */ "./src/js/parts/read-more.js");
/* harmony import */ var _pages_home__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./pages/home */ "./src/js/pages/home.js");
/* harmony import */ var _pages_page__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./pages/page */ "./src/js/pages/page.js");
/* harmony import */ var _pages_portfolio__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./pages/portfolio */ "./src/js/pages/portfolio.js");








(0,_parts_utils__WEBPACK_IMPORTED_MODULE_1__.ready)(() => {
  (0,_parts_navigation__WEBPACK_IMPORTED_MODULE_2__.initNavigation)();
  (0,_parts_animations__WEBPACK_IMPORTED_MODULE_3__.initScrollReveal)();
  (0,_parts_read_more__WEBPACK_IMPORTED_MODULE_4__.initPortfolioReadMore)();
  (0,_parts_read_more__WEBPACK_IMPORTED_MODULE_4__.initTeamReadMore)();
  (0,_pages_portfolio__WEBPACK_IMPORTED_MODULE_7__.initPortfolioTabs)();
  (0,_pages_home__WEBPACK_IMPORTED_MODULE_5__.initHomePage)();
  (0,_pages_page__WEBPACK_IMPORTED_MODULE_6__.initPage)();
});
})();

/******/ })()
;
//# sourceMappingURL=main.js.map