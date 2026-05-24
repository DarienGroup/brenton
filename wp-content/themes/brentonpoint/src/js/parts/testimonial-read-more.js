/**
 * Testimonial quotes — inline "Show more / Show less" toggle.
 *
 * Quotes longer than [data-truncate-chars] are clipped at the nearest word
 * boundary at or before that threshold. Clicking the inline toggle reveals
 * the rest in place; clicking again collapses it.
 */

const TRUNCATED_CLASS = 'testimonial-card__quote--truncated';
const EXPANDED_CLASS  = 'testimonial-card__quote--expanded';

function chevronSvg() {
  // Down chevron — flipped via CSS when expanded.
  return '<svg class="testimonial-card__toggle-icon" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true">'
       +   '<path d="M3 5l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>'
       + '</svg>';
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
  const rest    = fullText.slice(cut).replace(/^\s+/, '');
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
    quote.dispatchEvent(new CustomEvent('testimonial:toggle', { bubbles: true }));
  });

  quote.appendChild(visibleNode);
  quote.appendChild(restSpan);
  quote.appendChild(document.createTextNode(' '));
  quote.appendChild(toggle);
}

export function initTestimonialReadMore() {
  document.querySelectorAll('.testimonial-card__quote[data-truncate-chars]').forEach(init);
}
