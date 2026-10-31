# Alegria theme

Classic WordPress theme for Stichting Alegria. Pages are built from a set of custom, server-rendered blocks; events and newsletters are custom post types.

It started from [WP Rig](https://github.com/wprig/wprig), but almost nothing of that framework is left. It's plain PHP templates, a few hook files and one build script.

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

Build output (`assets/dist/`, `assets/blocks/*/build/`) is not in git, so run `npm run build` after pulling.

## Deploying

Run `npm run zip`, then upload `alegria.zip` under Appearance → Themes → Add New → Upload. The zip contains no sources, `node_modules` or build tooling.

## Where things are

| Path | What |
| --- | --- |
| `functions.php` | Loads the files in `inc/`. |
| `inc/setup.php` | Theme supports, the menu location, turning off comments, the default Posts type and the site editor. |
| `inc/post-types.php` | `newsletter` (`/nieuwsbrief`) and `event` (`/evenement`) post types, the event date field and archive ordering. |
| `inc/assets.php` | Front-end and editor stylesheet, navigation script, font preload. |
| `inc/blocks.php` | Registers every block in `assets/blocks` and makes them the only blocks available in the editor (no core or plugin blocks). |
| `inc/navigation.php` | Primary menu markup: dropdown icons, the "Doneer nu" arrow, parent links repeated in submenus. |
| `inc/helpers.php` | `alegria_svg()`, `alegria_block_image()`, `alegria_block_image_url()`, `alegria_asset_version()`. |
| `theme.json` | Editor settings: colour palette, layout widths, the Rubik font. |
| `assets/css/` | Theme CSS. `main.css` (front end) and `editor.css` are the entry points; `_*.css` are partials. |
| `assets/js/navigation.ts` | Mobile menu and submenu toggles. |
| `assets/blocks/` | One folder per block, plus `_shared/` for editor components the blocks share. |
| `assets/fonts/`, `assets/svg/` | Rubik variable font, inline SVG icons and logo. |
| `build.mjs` | The build: esbuild for JS, Lightning CSS for CSS. |

## Blocks

Each block is a folder in `assets/blocks/` named after the block (`wp-rig/hero` → `assets/blocks/hero/`):

```
block.json   name, title, attributes, and which scripts/styles to load
index.js     registers the block (reads the name from block.json)
edit.js      editor UI
render.php   front-end markup (all blocks are rendered on the server)
style.css    front-end styles, loaded only on pages that use the block
editor.css   editor-only styles
view.js      optional front-end script
```

All blocks use the `wp-rig/` namespace from the starter theme. **Don't rename it**: pages store block names in their content, and renaming would break every existing block.

To add a block:
1. Copy an existing block folder and rename it.
2. Change `name`, `title` and `attributes` in `block.json`.
3. Run `npm run build`.

It's registered and added to the inserter automatically.

### Editor UI

`ServerSideRender` does not work in this setup, so `render.php` is only used on the front end. In the editor, each block puts its fields in the sidebar (`InspectorControls`) and shows a simple preview built from its attributes. The building blocks for that live in `assets/blocks/_shared/`:

- `controls.js`:
  - `ImageControl` picks an image, stored as `<name>Id`, `<name>Url` and `<name>Alt`.
  - `LinkField` sets a URL through the link picker.
  - `RichTextField` is a sidebar rich text box.
  - `useMediaUrl()` returns an image URL for previews.
- `editor-preview.js`: `PreviewCard`, `PreviewField`, `PreviewThumb` and `PreviewEmptyState` for the preview.

In `render.php`, output images with `alegria_block_image()` (or `alegria_block_image_url()` for CSS backgrounds). It uses the attachment ID, so WordPress adds `srcset`, dimensions and lazy-loading.

Imports from `@wordpress/*` are not bundled. The build maps them to the `wp.*` globals and writes each block's dependencies to `build/index.asset.php`.

## WordPress setup

After a fresh install, set these in wp-admin:
- **Static front page:** Settings → Reading.
- **Permalinks:** "Post name".
- **Primary menu:** assign a menu to the "Hoofdmenu" location (Appearance → Menus).
- **"Doneer nu" item:** give it the CSS class `donate-menu-item`. Enable that field via Screen Options → CSS Classes.

Contact Form 7 is required for the contact and newsletter forms.
