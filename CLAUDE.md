# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

WordPress site for Stichting Alegria (Dutch nonprofit), run locally with Docker. Only `wordpress/wp-content/` is tracked; WP core, `wp-config.php`, plugins and uploads are gitignored. All custom work lives in the classic theme at `wordpress/wp-content/themes/alegria/`. It started from the [WP Rig](https://github.com/wprig/wprig) starter, but the WP Rig framework (component classes, `wp_rig()`, its build and test tooling) has been removed. Site content and UI copy, including the editor UI of the custom blocks, are in Dutch.

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
npm run dev     # same, unminified, rebuilding on every change under assets/
npm run zip     # production build + alegria.zip containing only runtime files, for upload
```

Everything is in `build.mjs`: esbuild for JS, Lightning CSS for CSS. There are no tests, linters or Composer. Build output (`assets/dist/`, `assets/blocks/*/build/`) is gitignored, so run `npm run build` after pulling or editing sources; otherwise the editor and front end load stale or missing assets. Files the container builds are owned by root.

## Architecture

**PHP.** `functions.php` only requires the files in `inc/`, which are plain hooks and functions with an `alegria_` prefix (no classes, no namespaces):
- `setup.php`: theme supports, the `primary` menu location, and turning off comments, the default Posts type and the site editor.
- `post-types.php`: the `newsletter` (`/nieuwsbrief`) and `event` (`/evenement`) CPTs, `event_date` meta and meta box, and event archive ordering.
- `assets.php`: enqueues `assets/dist/main.css` and `navigation.js`, the editor stylesheet and the font preload.
- `blocks.php`: block registration and the inserter allowlist.
- `navigation.php`: primary menu walker filters.
- `helpers.php`: `alegria_svg()`, `alegria_block_image()`, `alegria_block_image_url()`, `alegria_asset_version()`.

Asset versions are file mtimes.

**Custom blocks** (`assets/blocks/<slug>/`): `block.json`, `index.js` (registers using the name from `block.json`), `edit.js`, `render.php` (all blocks are server-rendered), `style.css`, `editor.css`, optionally `view.js`.
- **Registration and the allowlist.** `inc/blocks.php` registers every folder that has a `block.json`, and the `allowed_block_types_all` allowlist is exactly those blocks: no core or plugin blocks can be inserted. A new block folder is picked up automatically.
- **Imports.** Write editor code with `import … from '@wordpress/…'`. The build maps those imports to `wp.*` globals and writes the real dependency list to `build/index.asset.php`.
- **Folder names** match the block name without the namespace.
- **Keep the `wp-rig/` namespace.** All blocks use it. Pages store block names in `post_content`, so renaming it would break existing content.

**Do not use `ServerSideRender` in the block editor.** It does not work in this setup. `render.php` is for the front end only. In the editor, each block's `edit.js` puts its fields in `InspectorControls` and renders a client-side preview from its attributes. Shared pieces live in `assets/blocks/_shared/`:
- `controls.js`:
  - `ImageControl` stores `<name>Id`/`<name>Url`/`<name>Alt`.
  - `LinkField` and `RichTextField` are the link and rich-text sidebar fields.
  - `useMediaUrl()` resolves an image URL for previews.
- `editor-preview.js`: `PreviewCard`, `PreviewField`, `PreviewThumb` and `PreviewEmptyState`. See `txt/edit.js` for a minimal example.

In `render.php`, output images with `alegria_block_image()` / `alegria_block_image_url()` so WordPress adds `srcset` and dimensions.

**Templates.** Classic PHP templates:
- `index.php` renders pages (there is no `page.php`).
- `single.php` serves both CPTs.
- `archive-event.php` and `archive-newsletter.php` share `template-parts/archive/grid.php`.
- `404.php` is the not-found page.

The newsletter archive embeds a Contact Form 7 shortcode, and Contact Form 7 is required for the contact and newsletter forms.

**CSS.** Plain CSS with custom properties, custom media and nesting, compiled by Lightning CSS:
- **Entry points:** files in `assets/css/` not starting with `_`, i.e. `main.css` (front end, loaded on every page) and `editor.css`. Partials start with `_`. Each entry must `@import "_custom-media.css"` before using custom media queries.
- **Block styles:** each block's `style.css` loads only on pages that contain the block (`should_load_separate_core_block_assets`).
- **Breakpoint:** the mobile/desktop navigation switch is `--below-desktop` (87em), and `navigation.ts` uses the same value.

**theme.json** holds the editor settings:
- the brand palette, with custom colours and font sizes disabled
- layout widths
- the Rubik variable font (`assets/fonts/`), from which WordPress generates the `@font-face`

## Manual WordPress setup

After a fresh install, configure these in wp-admin: static front page (Settings → Reading), postname permalinks, and a menu assigned to the "Hoofdmenu" location. The "Doneer nu" menu item gets an arrow; give it the CSS class `donate-menu-item`, which you enable through Screen Options → CSS Classes.
