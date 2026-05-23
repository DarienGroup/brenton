# Brenton Point — Theme Development Guide

## Requirements

- Node.js 18+
- npm

## Setup

```bash
cd wp-content/themes/brentonpoint
npm install
```

## Build

```bash
npm run dev    # watch mode with source maps
npm run build  # production build (minified, no source maps)
```

Output lands in `dist/` and is enqueued by WordPress automatically:
- `dist/css/main.css`
- `dist/js/main.js`

---

## SCSS Architecture

```
src/scss/
├── abstracts/
│   ├── _variables.scss     # Design tokens: colors, typography, spacing, breakpoints, layout
│   ├── _functions.scss     # Sass helper functions (fluid-between, spacing, rem)
│   ├── _mixins.scss        # Reusable mixins (container, respond-to, flex-*)
│   └── _type-scale.scss    # Fluid typography mixin + token map
├── base/
│   ├── _reset.scss
│   ├── _typography.scss    # Base element styles + .text-{token} utility classes
│   └── _colors.scss        # .text-color-{name} / .bg-{name} utility classes
├── layout/
│   ├── _grid.scss          # .container, .section
│   ├── _header.scss
│   └── _footer.scss
├── components/
│   ├── _buttons.scss
│   ├── _forms.scss
│   └── _navigation.scss
├── pages/
│   ├── _home.scss
│   └── _page.scss
└── main.scss               # Entry point — @use only, no styles here
```

### Colors

The palette is defined in `abstracts/_variables.scss` as both raw variables and the `$palette` map. Each entry in the map automatically generates two utility classes:

```html
<p class="text-color-deep-teal">…</p>
<section class="bg-cream">…</section>
```

**Available tokens:** `white` · `black` · `secondary-gray` · `primary-gray` · `taupe` · `cream` · `deep-teal` · `sky-blue` · `blue-old` · `cyan`

To add a color: declare the raw variable, add it to `$palette` — the utility classes are generated automatically.

### Font Weights

Weight utility classes are generated from the `$font-weights` list in `abstracts/_variables.scss`:

```html
<p class="text-weight-300">Light</p>
<p class="text-weight-400">Regular</p>
<p class="text-weight-500">Medium</p>
<p class="text-weight-600">Semi-bold</p>
<p class="text-weight-700">Bold</p>
```

**Available weights:** `300` · `400` · `500` · `600` · `700`

> Note: `300` is only available for Lato; `500` and `600` are only available for Montserrat. Applying an unavailable weight causes the browser to synthesise or round to the nearest loaded weight.

To add or remove a step, edit `$font-weights` — the utility classes regenerate automatically on the next build.

### Fluid Typography

Design reference viewports: **1920px** (desktop) · **1440px** (medium) · **360px** (mobile).

Tokens are defined in `abstracts/_type-scale.scss`:

```scss
$type-scale: (
  'token': (size-at-1920, size-at-1440, size-at-360, line-height),
);
```

`line-height` accepts either a single value (constant across all breakpoints) or a 3-value list `(at-1920, at-1440, at-360)` for per-breakpoint values.

Each token generates a utility class automatically:

```html
<p class="text-body-L">…</p>
<h1 class="text-h1">…</h1>
```

To apply fluid type directly in SCSS:

```scss
@use '../abstracts/type-scale' as ts;

.my-element {
  @include ts.fluid-type(48, 36, 24);                         // constant line-height
  @include ts.fluid-type(64, 46, 34, (1.09, 1.139, 1.08));   // per-breakpoint line-height
  // params: size-1920, size-1440, size-360, line-height
}
```

To add a new token: add an entry to `$type-scale` — the utility class is generated automatically.

### Layout — Container

`.container` applies fluid horizontal padding based on the Figma artboard values:

| Viewport | Content width | Padding (each side) |
|----------|---------------|---------------------|
| 360px    | 320px         | 20px                |
| 1440px   | 1312px        | 64px                |
| 1920px   | 1440px        | 240px               |
| > 1920px | 1440px (locked) | 240px             |

```html
<div class="container">…</div>
```

### Layout — Sections

`.section` applies fluid vertical padding driven by CSS custom properties:

| Viewport | Padding |
|----------|---------|
| 360px    | 72px    |
| 1440px   | 140px   |
| 1920px   | 160px   |

Override for a specific section via `--section-py` (both sides), `--section-pt` (top only), or `--section-pb` (bottom only). Unset properties fall back to `--section-py`.

```html
<!-- override both sides equally -->
<section class="section" style="--section-py: 40px">…</section>

<!-- remove top padding -->
<section class="section" style="--section-pt: 0px">…</section>

<!-- independent top/bottom -->
<section class="section" style="--section-pt: 0px; --section-pb: 80px">…</section>
```

In SCSS:

```scss
.hero {
  --section-pt: 0px; // flush to header
}
```

For a fluid override use `fluid-between()`:

```scss
@use '../abstracts/functions' as *;
@use '../abstracts/variables' as *;

.cta {
  --section-pt: #{fluid-between(40, 80, 360, 1440)};
}
```

### Utility Functions

**`fluid-between($from-size, $to-size, $from-vw, $to-vw)`**
Returns a `clamp()` for linear interpolation between two viewport widths. All arguments are unitless px numbers. Works for both increasing and decreasing ranges.

```scss
padding: fluid-between(20, 64, 360, 1440); // → clamp(20px, calc(…), 64px)
```

**`spacing($multiplier)`** — returns `calc($spacing-unit * $multiplier)`.

**`rem($px)`** — converts a unitless px number to rem.

### Responsive Breakpoints

```scss
.element {
  font-size: 14px;
  @include respond-to(md) { font-size: 16px; }
  @include respond-to(xl) { font-size: 18px; }
}
```

| Key  | Min-width |
|------|-----------|
| `sm` | 576px     |
| `md` | 768px     |
| `lg` | 992px     |
| `xl` | 1200px    |
| `xxl`| 1400px    |

---

## JavaScript Architecture

```
src/js/
├── main.js             # Entry point — imports and initialises all modules
├── parts/
│   ├── utils.js        # ready(), qs(), qsa() DOM helpers
│   ├── navigation.js   # Mobile nav toggle
│   ├── animations.js   # Scroll-reveal
│   └── read-more.js    # Read More / Read Less toggle
└── pages/
    ├── home.js
    ├── page.js
    └── portfolio.js    # Portfolio tab filter
```

Everything compiles into a single `dist/js/main.js`. To add a new module: create a file in `parts/` or `pages/`, export an `init*` function, and call it from `main.js`.

### Read More toggle

Two pre-configured exports in `parts/read-more.js`:

- `initPortfolioReadMore()` — targets `.portfolio_block article`, triggers at 417 characters
- `initTeamReadMore()` — targets `.team_block article`, triggers at 20 000 characters

The toggle button is injected after `.entry-content` and toggles `.active` on it.

### Portfolio Tabs

`initPortfolioTabs()` in `pages/portfolio.js` filters portfolio articles by category:

- Tab buttons: `.portfolio-tabs button[data-block="All|Active|Realized"]`
- Articles must carry `category_portfolio-active` or `category_portfolio-realized` CSS classes (added by WordPress based on the post's category)
- Active tab gets `.active`, hidden articles get `.hide`

---

## PHP / WordPress

### `inc/` file map

| File | Responsibility |
|------|----------------|
| `setup.php` | Theme supports, `$content_width` |
| `enqueue.php` | Enqueue Google Fonts (Montserrat + Lato), `dist/css/main.css`, and `dist/js/main.js`; theme version used as cache-buster |
| `nav-menus.php` | Register Primary and Footer nav locations |
| `widgets.php` | Register main sidebar widget area |
| `custom-post-types.php` | Force `our_portfolio` ordering by `menu_order ASC` |
| `redirects.php` | 301-redirect single portfolio entries to `/portfolio/#anchor` |
| `helpers.php` | `brentonpoint_posted_on()`, `brentonpoint_posted_by()` template tags |

### Portfolio ordering

`our_portfolio` posts are always ordered by **menu_order ASC** on the front end. Set the order via the "Page Attributes → Order" field in the WordPress admin, or with a drag-and-drop reorder plugin.

### Portfolio redirects

Individual `our_portfolio` entries have no standalone template. Any direct visit is 301-redirected to `/portfolio/#slug`, where the anchor is `sanitize_title($post->post_title)`. The portfolio page is expected to contain matching anchor points.

---

## Git

The repository tracks only this theme (`wp-content/themes/brentonpoint/`). WordPress core, default themes, and all non-custom plugins are excluded by `.gitignore` at the repo root.

To start tracking a custom plugin, add an exception line in `.gitignore`:

```
!/wp-content/plugins/my-custom-plugin/
```
