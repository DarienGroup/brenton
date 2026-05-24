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

> **Path convention.** This README sits at the repo root, but everything tracked in the repo lives under `wp-content/themes/brentonpoint/`. Unless a path starts with `/` or `wp-content/`, treat it as relative to the theme directory — e.g. `src/scss/main.scss` means `wp-content/themes/brentonpoint/src/scss/main.scss`.

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

### Buttons

Styles in `components/_buttons.scss`. Markup produced by `brentonpoint_get_button()` (`inc/components.php`) — templates, the `[btn]` shortcode, and the GF submit-button filter all go through it.

**Templates**

```php
<?php brentonpoint_button([ 'label' => 'Get in Touch', 'variant' => 'cyan', 'href' => '/contact' ]); ?>
```

`brentonpoint_button()` echoes; `brentonpoint_get_button()` returns. Pull `label` / `href` from ACF; keep `variant` hardcoded in the template.

**Shortcode**

```
[btn variant="cyan" href="/contact"]Get in touch[/btn]
```

**Arguments**

| Arg | Default | Notes |
|-----|---------|-------|
| `label` | `''` | Button text. |
| `variant` | `'cyan'` | Key in `$button-variants`. |
| `href` | `''` | Non-empty → `<a>`. Empty → `<button>`. |
| `full` | `false` | Adds `btn--full`. |
| `target` | `''` | `_blank` auto-adds `rel="noopener noreferrer"`. |
| `type` | `'button'` | `<button>` only. `'submit'` for forms. |
| `class`, `id`, `rel`, `attrs` | — | Pass-through. |

**Color variants** are generated from the `$button-variants` map in `_buttons.scss`:

```scss
$button-variants: (
  'cyan': (
    bg-color:           $cyan,
    text-color:         $white,
    bg-color-hover:     $deep-teal,
    text-color-hover:   $white,
  ),
  // 'outline-cyan': (                          // border-color keys are optional
  //   bg-color:           transparent,
  //   text-color:         $cyan,
  //   border-color:       $cyan,
  //   bg-color-hover:     $cyan,
  //   text-color-hover:   $white,
  //   border-color-hover: $cyan,
  // ),
);
```

Each entry emits a `.btn--{name}` class. Optional `border-color` / `border-color-hover` keys are applied only when present — the base button reserves a transparent 1px border, so adding a border never shifts layout.

**Available variants:** `cyan` · `deep-teal`

**Modifiers**

| Class | Effect |
|-------|--------|
| *(none)* | Fixed `320px` wide (the default short button) |
| `.btn--full` | Stretches to `100%` of its container |

**Typography:** the label uses the fluid `'button'` token from `$type-scale` (18→16→18px, line-height 1) plus `letter-spacing: 0.05em`.

To add a new color: add one entry to `$button-variants` — the `.btn--{name}` class is generated automatically.

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
| `components.php` | `brentonpoint_get_button()` / `brentonpoint_button()` — renders `.btn` markup |
| `shortcodes.php` | Registers `[btn]` |
| `gravity-forms.php` | `gform_required_legend` ("* indicates required fields"); `gform_submit_button` → renders submit via `brentonpoint_get_button()` |

### Portfolio ordering

`our_portfolio` posts are always ordered by **menu_order ASC** on the front end. Set the order via the "Page Attributes → Order" field in the WordPress admin, or with a drag-and-drop reorder plugin.

### Portfolio redirects

Individual `our_portfolio` entries have no standalone template. Any direct visit is 301-redirected to `/portfolio/#slug`, where the anchor is `sanitize_title($post->post_title)`. The portfolio page is expected to contain matching anchor points.

---

## Inner Pages

Inner pages (Firm, Team, etc.) share a single page template that renders the standard chrome — header → page hero → page-specific sections → footer. Each inner page differs only in *which* sections it loads, resolved by slug at render time.

### Files

| File | Role |
|------|------|
| `page-templates/inner.php` | `Template Name: Inner page`. Renders the page hero and routes to a slug-matched sections file. |
| `template-parts/page-sections/{slug}.php` | Page-specific composition — just `get_template_part(...)` calls for that page's sections. |
| `template-parts/sections/{name}-section.php` | The individual section template parts (markup + ACF reads). |
| `template-parts/components/media-text.php` | Shared two-column image + text block. See [media-text component](#media-text-component). |

### How `inner.php` resolves sections

```php
$slug          = get_post_field('post_name', get_the_ID());
$sections_file = get_template_directory() . '/template-parts/page-sections/' . $slug . '.php';

if ($slug && file_exists($sections_file)) {
    get_template_part('template-parts/page-sections/' . $slug);
}
```

If no matching file exists, the page renders header + hero + footer only — no error.

### Adding a new inner page

1. **Create the page in WP admin.** Set the slug (e.g. `team`) and assign **Page Attributes → Template → Inner page**.
2. **Create the sections file** `template-parts/page-sections/{slug}.php`. List the sections in render order:

   ```php
   <?php
   defined('ABSPATH') || exit;

   get_template_part('template-parts/sections/team-leadership-section');
   get_template_part('template-parts/sections/team-values-section');
   ```

3. **Build each section template part** at `template-parts/sections/{name}-section.php` (markup + `get_field()` reads), plus a matching SCSS partial at `src/scss/components/_{name}-section.scss` registered in `main.scss`.
4. **Register the ACF field group** scoped to that page. With JSON sync enabled (see below), create the group in admin and commit the generated `acf-json/group_*.json` file.

### media-text component

`template-parts/components/media-text.php` is the shared image-left / text-right block (image stacks below content on mobile). Pass content via `$args` — the component does no ACF lookup itself.

```php
get_template_part('template-parts/components/media-text', null, [
    'image'         => get_field('firm_about_image'),     // ACF array | attachment ID | URL
    'heading'       => 'Brenton Point Capital Partners',
    'heading_class' => 'text-h3',                          // default; override per callsite (e.g. 'text-h2')
    'body'          => get_field('firm_about_body'),       // wpautop applied
    'button'        => null,                               // optional: ['label', 'href', 'target', 'variant']
    'reverse'       => false,                              // true → image on right at lg+
    'class'         => 'firm-about-section__media-text',   // optional extra class on the wrapper
]);
```

Styles live in `src/scss/components/_media-text.scss`. The component owns its internal spacing; the calling section owns vertical rhythm around it (e.g. by targeting the extra class passed via `class`).

### ACF field groups (JSON sync)

ACF local JSON sync is wired up in `inc/acf.php`. Field groups created or edited in **WP admin → Custom Fields** are auto-written to `acf-json/group_*.json` in the theme. Commit those JSON files alongside template / SCSS changes.

On other environments, **Custom Fields → Tools** shows the groups as syncable — click *Sync* to import the JSON into that environment's database. This keeps field structure in version control without manual export/import.

**Location rules.** For inner-page field groups, scope to the specific page (`Page is equal to {Page Title}`). Don't scope to `Page Template is equal to Inner page` — that would surface every inner page's fields on every inner page.

---

## Contact Section

A reusable "Get in Touch" section used on the homepage and a Contact page.

### Where it lives

| File | Role |
|------|------|
| `template-parts/sections/contact-section.php` | The component markup |
| `front-page.php` | Homepage; includes the section with `variant => 'home'` |
| `page-templates/contact.php` | Page Template "Contact"; includes the section with `variant => 'default'` |
| `src/scss/components/_contact-section.scss` | All styles, including scoped Gravity Forms overrides |
| `src/js/parts/contact-form.js` | Floating-label placeholder, legend repositioning, captcha load check |

To add the section to another page, create the appropriate template (or call directly):

```php
get_template_part(
    'template-parts/sections/contact-section',
    null,
    [ 'variant' => 'default' ]   // or 'home' for the gradient background
);
```

### Variants

| Value | Effect |
|-------|--------|
| `default` | Solid `$secondary-gray` background |
| `home` | Vertical gradient `#F5F5F3 → #FFFFFF` |

### Layout

| Viewport | Layout | Gaps |
|----------|--------|------|
| `< 992px` (mobile) | Single column, stacked: heading → description → cards → form | 40px |
| `992 – 1440px` (desktop) | 2×2 grid (heading \| description / cards \| form), row 2 vertically centred | row 72px, col 60px |
| `≥ 1441px` (wide) | Same 2×2 grid | row 72px, col 72px |

### ACF fields

**Global** — set on the *Site Settings* options page, reused by everything (footer + contact section):

| Field name | Type | Used for |
|------------|------|----------|
| `contact_form_id` | Number | Gravity Form ID rendered in the form column |
| `contact_email` | Email/Text | Contact Info card — email |
| `phone_number` | Text | Contact Info card — phone |
| `press_inquiries_email` | Email/Text | Press Inquiries card — email |
| `footer_address` | Textarea | Address card body (shared with the site footer) |

**Per page** — attach to the homepage and to any page using the *Contact* page template:

| Field name | Type | Notes |
|------------|------|-------|
| `contact_section_heading` | Text | Falls back to "Get in Touch with Us" |
| `contact_section_description` | Textarea | Optional |
| `contact_section_show_press` | True/False | Toggles the Press Inquiries card |
| `contact_section_info_heading` | Text | Card title, default "Contact Info" |
| `contact_section_press_heading` | Text | Card title, default "Press Inquiries" |
| `contact_section_address_heading` | Text | Card title, default "Address" |

If any per-page field is empty the template falls back to the defaults shown above, so nothing breaks while fields are being configured.

### Form rendering

The form is rendered server-side via `gravity_form( $form_id, false, false, false, null, true )`. The 6th argument (`true`) enables Gravity Forms' AJAX submission — submissions never trigger a full page reload. AJAX requires jQuery, which `inc/enqueue.php` declares as a dependency of `brentonpoint-main`.

The expected form should contain these field labels (any single Gravity Form will work as long as it has these — the form ID is stored in ACF):

- First Name *, Last Name *
- Email Address *
- Phone (optional)
- How can we help you? * (paragraph/textarea)
- A captcha field (invisible reCAPTCHA recommended)

### JS polish (`src/js/parts/contact-form.js`)

`initContactForm()` does three things on page-ready and again on Gravity Forms' `gform_post_render` event:

1. **Floating labels** — sets `placeholder=" "` on every input/textarea so the `:placeholder-shown` CSS selector can fade the overlaid label out when the field is filled.
2. **Required legend repositioning** — moves `.gform_required_legend` ("* indicates required fields") to sit immediately after the textarea field, with a `-4px` top margin so the gap above (textarea → legend) is 12px and the gap below (legend → captcha) remains 16px.
3. **Captcha load check** — 4 seconds after init, if `window.grecaptcha` is still undefined, adds `.is-captcha-unloaded` to the captcha field wrapper. The SCSS variant swaps the placeholder text to "⚠ Captcha not loaded" in red. Useful on local environments where Google's script is blocked.

### Styling notes

- The form inputs use a fixed `60px` height, the textarea `145px`. Browsers vertically centre input text natively, so a single `top: 30px` keeps the floating label aligned to the input centre even when GF appends error markup that grows `.gfield`.
- Gravity Forms ships its own theme framework whose stylesheet loads after `dist/css/main.css`. CSS variables on `.contact-section` (`--gform-theme-control-height`, `--gform-theme-control-textarea-min-height`, …) handle most cases; `!important` is used only on the few properties where GF's framework selectors win on specificity (label `font-size`, textarea `height`/`min-height`, input `box-shadow`).
- **Submit button** is the shared `.btn .btn--cyan .btn--full` component (see [Buttons](#buttons)), swapped in via the `gform_submit_button` filter in `inc/gravity-forms.php`. The button carries the `gform-theme__disable` class so GF's framework reset (`all: unset` on every descendant inside `.gform-theme--framework`) doesn't strip the `.btn` styles.
- **Grid quirks.** GF foundation ships a 12-track grid and places fields with `grid-column: span 12` (full) or `span 6` (half). The contact form uses a 2-track grid, so both `.gfield--width-full` and `.gfield--width-half` are remapped (`1 / -1` and `span 1` respectively) — otherwise CSS creates 10 implicit zero-width columns whose 16px gaps eat ~160px of the row.
- **`<fieldset>` Name fields.** GF renders Name fields as `<fieldset>`. Browsers give fieldsets a 2px groove border, `min-inline-size: min-content`, and special layout rules that ignore `justify-self: stretch` in grid — so `_contact-section.scss` resets `border: 0; min-inline-size: 0; inline-size: 100%` on `fieldset.gfield` to make First/Last Name fill their grid tracks like the `<div>`-based fields.
- Per-field error descriptions are hidden — the form-level error banner plus the red border + background on the field itself is enough.

---

## Git

The repository tracks only this theme (`wp-content/themes/brentonpoint/`). WordPress core, default themes, and all non-custom plugins are excluded by `.gitignore` at the repo root.

To start tracking a custom plugin, add an exception line in `.gitignore`:

```
!/wp-content/plugins/my-custom-plugin/
```
