export function ready(fn) {
  if (document.readyState !== 'loading') {
    fn();
  } else {
    document.addEventListener('DOMContentLoaded', fn);
  }
}

export function qs(selector, ctx = document) {
  return ctx.querySelector(selector);
}

export function qsa(selector, ctx = document) {
  return Array.from(ctx.querySelectorAll(selector));
}

export function on(el, event, handler, options = {}) {
  if (!el) return;
  el.addEventListener(event, handler, options);
}
