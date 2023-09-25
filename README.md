# johannschopplich.com

A powerful and performant integration of [Vite](https://vitejs.dev), [UnoCSS](https://github.com/antfu/unocss) and [Kirby](https://getkirby.com). This project seeks to provide a best practice that combines these three solutions while focusing on the developer experience.

## Installation

1. Duplicate the [`.env.development.example`](./.env.development.example) as `.env`:

```bash
cp .env.development.example .env
```

1. Install the required npm dependencies:

```bash
pnpm install
```

1. Install the required Composer dependencies:

```bash
composer install
```

## Usage

### Development

1. Start the Vite development server and watch for file changes accordingly:

```bash
pnpm run dev
```

1. Run the PHP built-in web server or use a development web server of your choice (like Laravel Valet).

```bash
composer start
```

### Production

Build the frontend assets:

```bash
pnpm run build
```

### Deployment

> ℹ️ See [ploi-deploy.sh](./scripts/ploi-deploy.sh) for deployment instructions.

## License

[MIT](./LICENSE) License © 2021-PRESENT [Johann Schopplich](https://github.com/johannschopplich)
