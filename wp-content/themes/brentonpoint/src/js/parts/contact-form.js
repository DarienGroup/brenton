const CAPTCHA_LOAD_TIMEOUT_MS = 4000;

const SELECTORS = {
  section:  '.contact-section',
  field:    '.ginput_container input, .ginput_container textarea',
  textarea: '.gfield--type-textarea, .gfield:has(textarea)',
  legend:   '.gform_required_legend',
  captcha:  '.gfield--type-captcha, .gfield--type-recaptcha, .gfield_captcha_container',
};

function injectPlaceholders(section) {
  section.querySelectorAll(SELECTORS.field).forEach((el) => {
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
  root.querySelectorAll(SELECTORS.section).forEach((section) => {
    injectPlaceholders(section);
    relocateRequiredLegend(section);
    watchCaptchaLoad(section);
  });
}

export function initContactForm() {
  polishContactForm();

  if (typeof window.jQuery === 'function') {
    window.jQuery(document).on('gform_post_render', () => polishContactForm());
  }
}
