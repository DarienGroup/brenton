import { qs, qsa } from './utils';

export function initAboutTabs() {
  const roots = qsa('[data-about-tabs]');
  if (!roots.length) return;

  roots.forEach((root) => {
    const tabs   = qsa('[data-about-tab]', root);
    const panels = qsa('[data-about-panel]', root);
    const labels = qsa('[data-about-label]', root);
    const labelsEl = qs('.about-tabs__badge-labels', root);
    if (!tabs.length || !panels.length) return;

    let current = tabs.find((t) => t.classList.contains('is-active'))?.dataset.aboutTab
                  || tabs[0].dataset.aboutTab;

    // Measure each label's natural width via an offscreen clone. The live
    // labels can't be measured directly: non-active ones sit at position
    // absolute with inset:0, so their offsetWidth reports the container's
    // width, not the text's. Cloning into the same parent inherits all
    // styling (font, weight, perspective context) for an accurate width.
    const widths = new Map();
    const measureWidths = () => {
      if (!labelsEl) return;
      labels.forEach((label) => {
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

    const applyWidth = (key) => {
      if (!labelsEl) return;
      const w = widths.get(key);
      if (typeof w === 'number') {
        labelsEl.style.width = `${w}px`;
      }
    };

    const activate = (key) => {
      if (key === current) return;

      root.dataset.aboutActive = key;

      tabs.forEach((tab) => {
        const isActive = tab.dataset.aboutTab === key;
        tab.classList.toggle('is-active', isActive);
        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
        tab.setAttribute('tabindex', isActive ? '0' : '-1');
      });

      panels.forEach((panel) => {
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
      labels.forEach((label) => {
        const key2 = label.dataset.aboutLabel;
        const wasActive = key2 === current;
        const isActive  = key2 === key;

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

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        activate(tab.dataset.aboutTab);
      });

      tab.addEventListener('keydown', (e) => {
        if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
        e.preventDefault();
        const idx  = tabs.indexOf(tab);
        const next = e.key === 'ArrowRight'
          ? tabs[(idx + 1) % tabs.length]
          : tabs[(idx - 1 + tabs.length) % tabs.length];
        next.focus();
        activate(next.dataset.aboutTab);
      });
    });
  });
}
