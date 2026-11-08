# Alegria theme

Classic WordPress theme for Stichting Alegria. Pages are built from custom, server-rendered blocks; events and newsletters are custom post types. See `CLAUDE.md` in the project root for the architecture.

## Development

Node lives in the `php` container, not on the host:

```bash
docker compose exec php bash
cd wp-content/themes/alegria
npm install

npm run dev     # build, then rebuild on every change to assets/ (Ctrl+C to stop)
npm run build   # production build (minified)
```

Build output (`assets/dist/`, `assets/blocks/*/build/`) is not in git, so run `npm run build` after pulling. Files built in the container are owned by root.

## WordPress setup

After a fresh install, set these in wp-admin:
- **Static front page:** Settings → Reading.
- **Permalinks:** "Post name".
- **Primary menu:** assign a menu to the "Hoofdmenu" location (Appearance → Menus).
- **"Doneer nu" item:** give it the CSS class `donate-menu-item`. Enable that field via Screen Options → CSS Classes.

Contact Form 7 is required for the contact and newsletter forms. Rank Math handles SEO tags, so set meta descriptions there.
