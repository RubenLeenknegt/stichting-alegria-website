# Website stichting Alegria

Basic local Docker setup for the Alegria WordPress website.

## Screenshots

### Hero

<img width="1919" height="820" alt="Screenshot 2026-04-13 204845" src="https://github.com/user-attachments/assets/3aeccfa6-e156-4a99-8f85-79b3fe728778" />

### About us section

<img width="1919" height="833" alt="Screenshot 2026-04-13 205149" src="https://github.com/user-attachments/assets/5d5aa542-4d1c-4993-b22e-7dcd9e04b07a" />


### Our projects
<img width="1919" height="882" alt="Screenshot 2026-04-13 204916" src="https://github.com/user-attachments/assets/20f3a5db-9a72-4fda-9604-4353c92e5a82" />


## Installation

1. Copy environment settings:

```bash
cp env.example .env
```

2. Fill in `DB_USER`, `DB_PASSWORD`, and `DB_ROOT_PASSWORD` in `.env`.

3. Start the stack:

```bash
docker compose up --build -d
```

4. Open:
- WordPress: `http://localhost:8080`
- phpMyAdmin: `http://localhost:8081`

## After WordPress Install

1. Create all pages.
2. Set `Settings -> Reading` to use static pages.
3. In `Appearance -> Menus`, make the menu with submenus and set display location to primary.
4. In Screen Options, enable custom CSS and add `donate-menu-item` to the "Doneer nu" button.
5. Set `Settings -> Permalinks` to `postname`.

## Theme Development Commands

Enter the PHP container:

```bash
docker compose exec php bash
```

Then go to the theme project root:

```bash
cd wp-content/themes/wprig
```

Run basic commands:

```bash
npm run build:blocks
npm run build:css
npm run block:new hero --dynamic --title "Hero"
```

