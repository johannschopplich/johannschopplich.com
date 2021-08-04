# johannschopplich.com

This website is based on the [Kirby](https://getkirby.com) CMS. [Vite](https://vitejs.dev) is used for handling CSS and JavaScript assets.

This is one of the few websites at the moment to display the capabilities of my CSS framework [Buldy](https://github.com/johannschopplich/buldy).

## Prerequisites

- Node.js with npm (only required to build CSS & JS assets)
- PHP 8.0+

## Setup

1. Duplicate the [`.env.example`](.env.example) as `.env` and optionally adapt its values depending on your environment (see below):

```bash
cp .env.example .env
```

1. Install npm dependencies:

```bash
npm install
```

**Note**: Composer dependencies are tracked in this repository by default. Executing `composer install` isn't necessary.

## Usage

### Development

1. Build assets and watch for changes accordingly:

```bash
npm run dev
```

1. Run the PHP built-in web server or use a development web server of your choice (like Laravel Valet).

```bash
./scripts/serve.sh
```

### Production

Build the frontend assets (CSS & JS files):

```bash
npm run build
```

If you have caching enabled, make sure to wipe the cache after each build:

```bash
rm -rf storage/cache/johannschopplich.com
```

> ℹ️ If you have set up the project on a server, just run `./scripts/deploy.sh`. It will take care of pulling latest changes and building the assets.

### Deployment

1. Deploy whole repository on your server.
2. Duplicate [`.env.example`](.env.example) as `.env` and set the following constants:
   - `KIRBY_DEBUG` to `false`
3. Install npm dependencies and build assets:
   - `npm i && npm run build`
4. Point your web server to the `public` folder.
5. For Apache web servers: Some hosting environments require to uncomment `RewriteBase /` in [`.htaccess`](public/.htaccess) to make site links work.
