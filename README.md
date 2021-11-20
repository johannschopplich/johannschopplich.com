# johannschopplich.com

> Forked from [kirby-vite-unocss-kit](https://github.com/johannschopplich/kirby-vite-unocss-kit#readme). Head over to its repository to find out more about it.

A powerful and performant integration of [Vite](https://vitejs.dev), [UnoCSS](https://github.com/antfu/unocss) and [Kirby](https://getkirby.com). This project seeks to provide a best practice that combines these three solutions while focusing on the developer experience.

## Installation

1. Duplicate the [`.env.example`](.env.example) as `.env` and optionally adapt its values depending on your environment:

```bash
cp .env.example .env
```

1. Install the required dependencies:

```bash
npm install
composer install
```

## Usage

### Development

1. Build assets and watch for changes accordingly:

```bash
npm run dev
```

1. Run the PHP built-in web server or use a development web server of your choice (like Laravel Valet).

```bash
composer start
```

### Production

Build the frontend assets (CSS & JS files):

```bash
npm run build
```

### Deployment

1. Deploy whole repository on your server.
2. Duplicate [`.env.example`](.env.example) as `.env`.
3. Install dependencies:
   - `npm install`
   - `composer install`
4. Build frontend assets:
   - `npm run build`
5. Point your web server to the `public` folder.
6. For Apache web servers: Some hosting environments require to uncomment `RewriteBase /` in [`.htaccess`](public/.htaccess) to make site links work.

## License

[MIT](./LICENSE) License © 2021 [Johann Schopplich](https://github.com/johannschopplich)
