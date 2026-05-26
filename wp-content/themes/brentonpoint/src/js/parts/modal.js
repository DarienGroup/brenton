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
export function bindModal( dialog, options = {} ) {
  if ( ! dialog || typeof dialog.close !== 'function' ) return dialog;

  const closeSelector = options.closeSelector || '[data-modal-close]';

  // Click on the dialog element itself = click on the backdrop, because all
  // visible content lives inside a child wrapper. (If a caller renders content
  // as a direct child of <dialog>, the click target will be that child and
  // this listener won't fire.)
  dialog.addEventListener( 'click', ( event ) => {
    if ( event.target === dialog ) {
      dialog.close();
    }
  } );

  dialog.querySelectorAll( closeSelector ).forEach( ( btn ) => {
    btn.addEventListener( 'click', () => dialog.close() );
  } );

  return dialog;
}

/**
 * Convenience wrapper: dialog.showModal() with the SSR-safety guard.
 *
 * @param {HTMLDialogElement} dialog
 */
export function openModal( dialog ) {
  if ( dialog && typeof dialog.showModal === 'function' && ! dialog.open ) {
    dialog.showModal();
  }
}
