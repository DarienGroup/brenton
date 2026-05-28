import { bindModal, openModal } from './modal';

/**
 * Open the shared `#form-success-popup` dialog whenever a Gravity Form on the
 * page finishes submitting via AJAX, and restore the form so it's visible
 * (and resettable) again — by default Gravity Forms swaps the form markup for
 * an inline confirmation message; we cache and re-insert the pristine form.
 */

const wrapperCache = new Map(); // form id (string) → outerHTML snapshot

function cacheWrapper(wrapper) {
  if (!wrapper) return;
  const id = wrapper.id.replace('gform_wrapper_', '');
  if (!id || wrapperCache.has(id)) return;
  wrapperCache.set(id, wrapper.outerHTML);
}

function cacheAllWrappers(root = document) {
  root.querySelectorAll('[id^="gform_wrapper_"]').forEach(cacheWrapper);
}

function restoreForm(formId) {
  const html = wrapperCache.get(String(formId));
  if (!html) return;

  const current =
    document.getElementById(`gform_wrapper_${formId}`) ||
    document.getElementById(`gform_confirmation_wrapper_${formId}`);
  if (!current) return;

  const template = document.createElement('template');
  template.innerHTML = html.trim();
  const fresh = template.content.firstElementChild;
  if (!fresh) return;

  current.replaceWith(fresh);

  if (typeof window.jQuery === 'function') {
    window.jQuery(document).trigger('gform_post_render', [Number(formId), 1]);
  }
}

export function initFormSuccess() {
  const dialog = document.getElementById('form-success-popup');
  if (!dialog) return;

  bindModal(dialog);

  cacheAllWrappers();

  if (typeof window.jQuery !== 'function') return;

  // Re-cache any wrappers that render later (e.g. forms inside lazy sections).
  window.jQuery(document).on('gform_post_render', (_event, formId) => {
    const wrapper = document.getElementById(`gform_wrapper_${formId}`);
    if (wrapper && !wrapperCache.has(String(formId))) {
      cacheWrapper(wrapper);
    }
  });

  window.jQuery(document).on('gform_confirmation_loaded', (_event, formId) => {
    restoreForm(formId);
    openModal(dialog);
  });
}
