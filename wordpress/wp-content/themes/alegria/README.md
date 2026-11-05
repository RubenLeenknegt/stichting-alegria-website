# Alegria theme

Classic WordPress theme for Stichting Alegria. Pages are built from a set of custom, server-rendered blocks, and events and newsletters are custom post types.

It started from [WP Rig](https://github.com/wprig/wprig), but almost nothing of that framework is left: plain PHP templates, six small hook files and one build script.

## Development

Node lives in the `php` container, not on the host:

```bash
docker compose exec php bash
cd wp-content/themes/alegria
npm install

npm run dev     # build, then rebuild on every change to assets/ (Ctrl+C to stop)
npm run build   # production build (minified)
npm run zip     # production build + alegria.zip with only the files the site needs
```

Build output (`assets/dist/`, `assets/blocks/*/build/`) is not in git, so run `npm run build` after pulling. Files built in the container are owned by root.

## Deploying

1. Run `npm run zip`.
2. Upload `alegria.zip` under Appearance → Themes → Add New → Upload, and activate it. The zip has no sources, `node_modules` or build tooling.

Activating the theme rebuilds the URL rules. If URLs ever 404 after a change to the post types, save Settings → Permalinks once.

## Where things are

| Path | What |
| --- | --- |
| `functions.php` | Loads the files in `inc/`. |
| `inc/setup.php` | Theme supports, the "Hoofdmenu" location, turning off comments, the default Posts type and the site editor. |
| `inc/post-types.php` | The post types: `newsletter` at `/nieuwsbrief/` and `event` at `/evenement/` (old `/newsletters/` and `/events/` URLs redirect). Also the event date field and archive ordering. |
| `inc/assets.php` | Front-end and editor stylesheet, navigation script, font preload, per-block CSS loading. |
| `inc/blocks.php` | Registers every block in `assets/blocks` and makes them the only blocks available in the editor (no core or plugin blocks). |
| `inc/navigation.php` | Primary menu markup: dropdown icons, the "Doneer nu" button, parent links repeated in submenus. |
| `inc/helpers.php` | `alegria_svg()`, `alegria_block_image()`, `alegria_block_image_url()`, `alegria_asset_version()`. |
| `*.php`, `template-parts/` | Templates: `index.php` (pages), `single.php` (an event or newsletter), the two archives with a shared card grid, `404.php`, header and footer parts. |
| `theme.json` | Editor settings: colour palette, layout widths, the Rubik font. |
| `assets/css/` | Theme CSS. Entry points are `main.css` (front end, every page) and `editor.css`; `_*.css` are partials. |
| `assets/js/navigation.ts` | Mobile menu and submenu toggles. |
| `assets/blocks/` | One folder per block, plus `_shared/` for editor components the blocks share. |
| `assets/fonts/` | Rubik variable font (Latin WOFF2, weights 300–900). |
| `assets/svg/` | Logo, social icons, arrow and dropdown icon, inlined with `alegria_svg( 'name' )`. |
| `build.mjs` | The build: esbuild for JS, Lightning CSS for CSS. |

## Styles

- **No preprocessor:** the CSS is plain CSS (custom properties, `@custom-media`, nesting), compiled by Lightning CSS.
- **Breakpoints** are in `assets/css/_custom-media.css`. The navigation switches to its mobile layout below 87em (`--below-desktop`).
- **Block styles** live in each block's `style.css` and only load on pages that use the block.
- **Colours and spacing tokens** are in `_custom-properties.css`. The editor's colour palette is in `theme.json`, and the two are kept in sync by hand.

## Blocks

Each block is a folder in `assets/blocks/` named after the block (`wp-rig/hero` → `assets/blocks/hero/`):

```
block.json   name, title, attributes, and which scripts/styles to load
index.js     registers the block (reads the name from block.json)
edit.js      editor UI
render.php   front-end markup (all blocks are rendered on the server)
style.css    front-end styles, loaded only on pages that use the block
editor.css   optional editor-only styles
view.js      optional front-end script (upcoming-events and scrolllint: Swiper, via the shared alegria-swiper handle)
```

All blocks use the `wp-rig/` namespace from the starter theme. **Don't rename it**: pages store block names in their content, and renaming would break every existing block. Attribute names are stored in the content too, so don't rename those either.

To add a block:
1. Copy an existing block folder and rename it.
2. Change `name`, `title` and `attributes` in `block.json`.
3. Run `npm run build`.

The new block is registered and added to the inserter automatically.

### Editor UI

`ServerSideRender` does not work in this setup, so `render.php` is only used on the front end. In the editor, each block puts its fields in the sidebar (`InspectorControls`) and shows a simple preview built from its attributes. The building blocks for that live in `assets/blocks/_shared/`:

- `controls.js`:
  - `ImageControl` picks an image, stored as `<name>Id`, `<name>Url` and `<name>Alt`.
  - `LinkField` sets a URL through the link picker.
  - `RichTextField` is a sidebar rich text box (bold, italic, link).
  - `useMediaUrl()` returns an image URL for previews.
- `editor-preview.js`: `PreviewCard`, `PreviewField`, `PreviewThumb` and `PreviewEmptyState` for the preview.

Editor labels are in Dutch, with the `alegria` text domain.

In `render.php`, output images with `alegria_block_image()` (or `alegria_block_image_url()` for CSS backgrounds). It uses the attachment ID, so WordPress adds `srcset`, dimensions and lazy-loading. Pass one of the `alegria-*` image sizes registered in `inc/setup.php`; after changing sizes, regenerate thumbnails.

Imports from `@wordpress/*` are not bundled. The build maps them to the `wp.*` globals and writes each block's dependencies to `build/index.asset.php`.

## WordPress setup

After a fresh install, set these in wp-admin:
- **Static front page:** Settings → Reading.
- **Permalinks:** "Post name".
- **Primary menu:** assign a menu to the "Hoofdmenu" location (Appearance → Menus).
- **"Doneer nu" item:** give it the CSS class `donate-menu-item`. Enable that field via Screen Options → CSS Classes.

Plugins:
- **Contact Form 7** is required for the contact and newsletter forms.
- **Rank Math** handles SEO tags, so set meta descriptions there.
