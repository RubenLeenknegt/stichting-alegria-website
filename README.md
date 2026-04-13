# Alegria (WordPress + Docker)

Basic local Docker setup for the Alegria WordPress website.

## Screenshots

![Homepage](docs/screenshots/homepage.png)
![About page](docs/screenshots/about.png)
![Donate section](docs/screenshots/donate.png)

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
cd /var/www/html/wp-content/themes/wprig
```

Run basic commands:

```bash
npm run build:blocks
npm run build:css
npm run block:new hero --dynamic --title "Hero"
```

