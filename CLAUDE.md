# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

WordPress site for Stichting Alegria (Dutch nonprofit), run locally with Docker. Only `wordpress/wp-content/` is tracked; WP core, `wp-config.php`, plugins and uploads are gitignored. All custom work lives in the classic theme at `wordpress/wp-content/themes/alegria/`. It started from the [WP Rig](https://github.com/wprig/wprig) starter, but the WP Rig framework (component classes, `wp_rig()`, its build, test and lint tooling, Composer) has been removed. Don't reintroduce it. Site content and UI copy, including the editor UI of the custom blocks, are in Dutch.

Active plugins: Contact Form 7 (contact and newsletter forms; the newsletter archive embeds one by shortcode), Flamingo, Rank Math SEO (writes the `<head>` SEO tags; set meta descriptions there, not in the theme. Its breadcrumbs are switched on by the theme's `rank-math-breadcrumbs` support and printed by the `post-header` block), Noptin, Donorbox, FluentSMTP, WebP Converter for Media.

## Environment & Commands

```bash
cp env.example .env              # then fill DB_USER, DB_PASSWORD, DB_ROOT_PASSWORD
docker compose up --build -d     # WordPress :8080, phpMyAdmin :8081
docker compose exec php bash     # Node 20 and npm live in this container, not on the host
cd wp-content/themes/alegria     # theme root inside the container (/var/www/html is ./wordpress)
```

Theme commands (run from the theme root inside the `php` container):

```bash
npm run build   # production build: assets/css + assets/js -> assets/dist, each block -> assets/blocks/<slug>/build
npm run dev     # same, unminified, rebuilding on every change under assets/ (Ctrl+C to stop)
npm run zip     # production build + alegria.zip containing only runtime files, for upload
```

Everything is in `build.mjs`: esbuild for JS, Lightning CSS for CSS. There are no tests or linters.
- **After pulling or editing sources, run `npm run build`.** Build output (`assets/dist/`, `assets/blocks/*/build/`) is gitignored, so otherwise the editor and front end load stale or missing assets.
- **Build output is owned by root,** because the container builds it.
- **CSS watching polls file mtimes** in `npm run dev`, because file events don't reliably cross the Docker bind mount.

Deploying: run `npm run zip` and upload `alegria.zip` as a theme in wp-admin. Activating it flushes the rewrite rules.

## Architecture

**PHP.** `functions.php` only requires the files in `inc/`. These are plain hooks and functions with an `alegria_` prefix: no classes, no namespaces.
- `setup.php`:
  - theme supports and the menu locations: `primary` ("Hoofdmenu") and the three footer columns `footer-info`, `footer-projects`, `footer-contact` ("Footer: over ons / projecten / contact");
  - turns off comments, the default Posts type and the site editor;
  - points the blog index query at newsletters.
- `post-types.php`:
  - the `newsletter` (`/nieuwsbrief/`) and `event` (`/evenement/`) CPTs, with Dutch admin labels;
  - the `event_date` meta and its date meta box;
  - the block `template` both CPTs start with (`alegria_single_template()`): `post-header`, a `txt` block, `share`, `read-more`, with all but `txt` locked against moving and removal. It only applies to new posts;
  - the event archive ordered by `event_date` DESC;
  - 301 redirects from the old `/newsletters/` and `/events/` URLs;
  - `flush_rewrite_rules` on theme activation.
- `assets.php`:
  - enqueues `assets/dist/main.css` and the deferred `assets/dist/navigation.js`, with its localized `alegriaNav` strings;
  - the editor stylesheet `assets/dist/editor.css`;
  - registers (does not enqueue) the shared `alegria-swiper` script and style, `assets/dist/swiper.{js,css}`;
  - the font preload;
  - `should_load_separate_core_block_assets`.
- `blocks.php`: registers every block folder and limits the editor to those blocks; `share` and `read-more` are only offered on newsletters and events.
- `navigation.php`: primary-menu walker filters.
  - Every parent gets a `.dropdown` icon; `navigation.ts` turns it into a toggle button.
  - Items whose URL is empty or `#` become a `<button class="submenu-toggle">`.
  - The "Doneer nu" item (CSS class `donate-menu-item`, or a title starting with "Doneer nu") is a `.btn-primary` link with the arrow inside it (`alegria_is_donate_item()`).
  - Each parent with a URL is repeated as the first submenu item, with class `menu-item--injected-parent-link` (hidden on desktop).
- `customizer.php`: the Appearance → Customize → Footer section (intro text, Instagram/Facebook/LinkedIn URLs, CTA button and text link), read with `alegria_footer_setting( $id )`. Empty settings are left out of the footer.- `helpers.php`:
  - `alegria_svg( $name )` returns the inline markup of `assets/svg/<name>.svg`.
  - `alegria_block_image()` / `alegria_block_image_url()` render block images.
  - `alegria_display_date( $post_id )` returns the date to show (`iso`, `text`): the event date for events, the publish date otherwise.
  - `alegria_asset_version()` returns a file's mtime, used for asset versions.

**Templates.** Classic PHP templates; there is no block theme, `templates/` folder or site editor.
- **Pages:** `index.php` renders them through `template-parts/content/entry.php` (there is no `page.php`). It prints no title, and skips the featured image when the page has a `post-header` block.
- **Singles:** `single.php` serves both CPTs. Posts with the `post-header` block get their title from it; older posts without it get a plain `<h1>`.
- **Archives:** `archive-event.php` and `archive-newsletter.php` share `template-parts/archive/grid.php`.
- **Errors and empty states:** `404.php` is the not-found page; `template-parts/content/none.php` covers empty results.
- **Header and footer:** `header.php` and `footer.php` use `template-parts/header/{branding,mobile-menu-toggle,navigation}.php` and `template-parts/footer/info.php`.
- **Footer layout:** on the left the logo (an `<img>` of `assets/svg/logo.svg`), intro text, round social links and the actions (a `.btn-primary` and a text link); on the right the three footer menus, each headed by its menu's name and shown only when a menu is assigned; below, a bottom bar with copyright and legal links. Stacked below `--wide-query` (64em), with the menus in three columns from `--content-query`.

The logo and social icons are inline SVGs. The mobile menu button is pure CSS.

**Custom blocks** (`assets/blocks/<slug>/`): `block.json`, `index.js`, `edit.js`, `render.php` (all blocks are server-rendered), `style.css`, optionally `editor.css`, and optionally `view.js` (`upcoming-events` and `scrolllint`, both Swiper, and `share`).
- **index.js:** registers using the name from `block.json`.
- **Registration and the allowlist.** `inc/blocks.php` registers every folder that has a `block.json`. The `allowed_block_types_all` allowlist is exactly those blocks, so no core or plugin blocks can be inserted. A new block folder is picked up automatically.
- **Imports.** Write editor code with `import … from '@wordpress/…'`. The build maps those imports to `wp.*` globals and writes the real dependency list to `build/index.asset.php`.
- **Folder names** match the block name without the namespace.
- **Keep the `wp-rig/` namespace.** All blocks use it. Pages store block names in `post_content`, so renaming it would break existing content. It's also why block wrapper classes are `wp-block-wp-rig-<slug>`.
- **The blocks:**
  - `hero`, `aboutus`, `cards` and `txt-img` (text and button beside an image; renders nothing until image and button are set; formerly `feature-split`);
  - `newsletter-overview` (latest 3) and `upcoming-events` (renders nothing when no event is dated yesterday or later);
  - `scrolllint` (decorative image strip, a continuous Swiper marquee), `txt`, `img`;
  - `contact-form`: title, two texts and the green social links (the footer's Customizer URLs) beside the CF7 form "Contact formulier", whose shortcode is hardcoded in `render.php`. The form and mail templates (stored in the database) are in `cf7-templates.md` in the block folder;
  - `embed-shortcode`: raw iframe/HTML only for authors with `unfiltered_html`; shortcode text is sanitized before `do_shortcode`;
  - `post-header` ("Paginakop", `multiple: false`, usable everywhere): Rank Math breadcrumbs, `<h1>`, intro, 16:9 `alegria-feature` image falling back to the featured image; only newsletters and events also show their date;
  - the newsletter/event-only template blocks (`multiple: false`): `share` (Facebook/LinkedIn share URLs and a copy-link button in `view.js`; no Instagram, which has no web share URL) and `read-more` (three others of the same type; events: upcoming first, then past. Reuses the `newsletter-overview` markup and lists its style handle in block.json).

**Do not use `ServerSideRender` in the block editor.** It does not work in this setup. `render.php` is for the front end only. In the editor, each block's `edit.js` puts its fields in `InspectorControls` and renders a client-side preview from its attributes. Shared pieces live in `assets/blocks/_shared/`:
- `controls.js`:
  - `ImageControl` stores `<name>Id`/`<name>Url`/`<name>Alt`.
  - `LinkField` and `RichTextField` are the link and rich-text sidebar fields (bold, italic, link).
  - `useMediaUrl()` resolves an image URL for previews.
- `editor-preview.js`: `PreviewCard`, `PreviewField`, `PreviewThumb` and `PreviewEmptyState`. See `txt/edit.js` for a minimal example.

Editor strings use the `alegria` text domain and are written in Dutch. Don't change attribute names: existing content stores them.

In `render.php`, output images with `alegria_block_image( $id, $url, $alt, $size, $attr )`, or `alegria_block_image_url()` for CSS backgrounds. These use the attachment ID when it exists, so WordPress adds `srcset`, dimensions and lazy-loading, and fall back to the stored URL.

**Image sizes.** Every image call uses a named size registered in `inc/setup.php` (no core `large`/`full`):
- `alegria-card` 900×600 crop: event/newsletter cards (3:2);
- `alegria-hero` 1920×1080 crop, `alegria-portrait` 600×800 crop (cards), `alegria-square` 600×600 and `alegria-wide` 1200×600 crop (about-us), `alegria-feature` 1200×675 crop (txt-img, upcoming-events slider);
- `alegria-logo` 160px tall (scrolllint), `alegria-content` 1600px wide (img block, page featured image).

After adding or changing a size, regenerate thumbnails for existing uploads; until then WordPress serves the closest existing file.

**Swiper** (npm `swiper` 11) powers `upcoming-events` and `scrolllint`. `assets/js/swiper.js` bundles it with the A11y, Autoplay, Keyboard, Navigation and Pagination modules and sets `window.Swiper`; `assets/css/swiper.css` bundles its CSS. In block scripts, `build.mjs` resolves `import Swiper from 'swiper'` to that global and adds `alegria-swiper` to `build/view.asset.php`. The blocks list `alegria-swiper` first in their block.json `style`. So Swiper loads once, only on pages with one of these blocks. Theme it through its `--swiper-*` custom properties in the block's `style.css`.

## Front end (CSS, JS, fonts)

**CSS** is plain CSS with custom properties, custom media and nesting, compiled by Lightning CSS. Browser targets are in `.browserslistrc`.
- **Entry points** are the files in `assets/css/` that don't start with `_`:
  - `main.css` is the front end, loaded on every page.
  - `swiper.css` is the Swiper bundle, loaded only through the `alegria-swiper` handle.
  - `editor.css` is the editor canvas.
  - Each entry must `@import "_custom-media.css"` before using custom media queries.
- **`main.css` imports, in order:** `_custom-properties`, `_custom-media`, `_reset`, `_typography`, `_elements`, `_links`, `_header` (which imports `_navigation`), `_footer`, `_accessibility`, `_buttons`, `_contact-form` (CF7 forms), `_media` (iframe sizing), `_forms`, `_content` (page content, archives, single pages, pagination, alignments, layout).
- **Block styles live with each block**, in its `style.css`. WordPress inlines them only on pages that contain the block. Don't put block styles in `main.css`.
- **Breakpoints** are custom media in `_custom-media.css`, in range syntax so they don't overlap. The mobile/desktop navigation switch is `--below-desktop` `(width < 87em)` / `--above-desktop` `(width >= 87em)`, and `navigation.ts` uses the same value via `matchMedia`. `--content-query` is `(width >= 48em)`, `--wide-query` `(width >= 64em)`. Block stylesheets are built separately and write their media queries out (use `47.99em` / `48em` style pairs so they don't overlap).
- **Font sizes** (`--font-size-*`) are computed from `--global-font-size: 20`. The scale is uneven on purpose (it is what the design was built on): `-regular` divides by 16 (20px) while the others divide by 20, so `-small` is 12.8px, `-medium` 19.2px, `-large` 28.8px, and `-larger` and `-xl` are both 38.4px. Don't "fix" it; components are tuned to these values.
- **Widths:** `--content-width` 50rem and `--wide-width` 75rem match theme.json's `contentSize`/`wideSize`; the editor uses them too.
- **Buttons:** `.btn-primary` (`_buttons.css`) is the red CTA (`--color-theme-red`, hover `--color-theme-dark-red`), with an optional `<span class="btn-icon">` arrow. The CF7 submit (`.cf7-submit-btn`, class set in the form templates in the database) shares it. `.btn-secondary` is the hero's white text link.
- **Two colour systems coexist:**
  - The CSS uses its own tokens from `_custom-properties.css` (`--color-theme-primary` #1D9741, `--color-theme-red` #D32F2F, `--color-theme-light-grey`, `--header-background-color`, `--footer-background-color` #11652B, `--font-size-*`, …).
  - `theme.json` defines the editor palette (`primary`, `primary-dark`, `secondary`, `light-grey`, `black`, `white` → `--wp--preset--color--*`).

  These aren't linked; keep them in sync, or make one derive from the other.
- **Navigation state classes** used by `_navigation.css` / `_header.css`:
  - `.nav--toggled-on` on the nav while the mobile menu is open;
  - `.menu-item--toggled-on` on an open parent `li`, with `.toggle-show` on its submenu;
  - `.dropdown-toggle` is the generated submenu button;
  - `.menu-item--has-toggle` is set on parents.

**JS:** `assets/js/navigation.ts` is the only theme script (mobile menu toggle, submenu toggles, keyboard focus handling). It builds to `assets/dist/navigation.js`.

**Fonts:** Rubik, as a single variable Latin WOFF2 (`assets/fonts/rubik-latin-wght-normal.woff2`, weights 300–900). `theme.json` declares it (`fontFace`), WordPress prints the `@font-face` on the front end and in the editor, and `inc/assets.php` preloads it. The CSS refers to it as `"Rubik"` via `--global-font-family`. There are no Google Fonts.

**theme.json:**
- the brand palette, with custom colours, gradients and duotone off;
- no font-size presets;
- `appearanceTools` off;
- layout `contentSize` 800px and `wideSize` 1200px;
- the Rubik font.

## Known gaps (content and copy, not code)

- **Copy:**
  - The footer content (intro text, social links, CTA) is empty until it is filled in the Customizer.
  - The privacy and cookie links go to `/privacy-policy/` and `/cookie-policy/`.
  - The skip link reads "Skip to content" (English).
- **Settings:**
  - The site language is still English (`lang="en-US"`).
  - Most block images have no alt text.
  - Pages have no meta descriptions (Rank Math).
- **Events archive:** it says "aankomende evenementen" but lists all events, newest first.
- **Stale page and menu links:**
  - A page with the slug `nieuwsbrief` is set as the Posts page. It's shadowed by the newsletter archive and should be removed.
  - The menu's custom links still use `/events/` and `/newsletters/`; they redirect to the new URLs.

## Manual WordPress setup

After a fresh install, configure these in wp-admin:
- **Static front page:** Settings → Reading.
- **Permalinks:** "Post name". Save again after changing post type slugs.
- **Menus:** assign one to the "Hoofdmenu" location, and one to each of the three "Footer: …" locations. A footer column's heading is its menu's name.
- **Footer content:** Appearance → Customize → Footer.
- **"Doneer nu" item:** it becomes a red button with an arrow. Give it the CSS class `donate-menu-item`, which you enable through Screen Options → CSS Classes (a title starting with "Doneer nu" also works).
- **Thumbnails:** after installing the theme, or after changing an image size, regenerate thumbnails (the Regenerate Thumbnails plugin, or `wp media regenerate --yes` where wp-cli is available).
