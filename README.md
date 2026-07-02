<div align="center">
  <img src="./public/assets/icon.svg" alt="Johann Schopplich logo" width="120">

# johannschopplich.com

My personal website – writing, projects and photography.

[Website](https://johannschopplich.com) •
[Blog](https://johannschopplich.com/blog)

</div>

## Why

This is my home on the web: a bilingual (English/German) site with articles reaching back to 2019, projects of the heart, photography and the odd bit of CV. No analytics, no tracking.

It's also where my Kirby + Vite setup was worked out in the first place – the integration that became the shared base of my other Kirby sites, from [realtroll.de](https://github.com/johannschopplich/realtroll.de) to [bildhauer-volkmar-kuehn.de](https://github.com/johannschopplich/bildhauer-volkmar-kuehn.de).

## How It's Built

- [Kirby 5](https://getkirby.com) – flat-file CMS, content as folders and text files
- [Vite](https://vite.dev) + [UnoCSS](https://unocss.dev) (preset-wind4) + TypeScript – styling and frontend build
- [kirby-helpers](https://github.com/johannschopplich/kirby-helpers) – meta, redirects, robots.txt and sitemap
- [Kirby Copilot](https://kirby.tools/copilot) + [Kirby Content Translator](https://kirby.tools/content-translator) – Panel writing aids

There is no frontend bundle to speak of: [`src/main.ts`](./src/main.ts) installs small modules (theme, navigation, image zoom, lazy loading) and lazy-loads per-template scripts keyed on `document.body.dataset.template`. The interactive bits are web components – scramble-text, sparkly-text, click-spark, masonry-grid and friends – plus a pinch of Alpine.js.

## Notable Details

- **Markdown for LLMs.** Every page has a markdown representation: send an `Accept: text/markdown` header and [`routes.php`](./site/config/routes.php) serves clean frontmatter + blocks markdown rendered by `*.md.php` templates.
- **Structured data.** The [site plugin](./site/plugins/johannschopplich) emits a schema.org graph (Person, WebSite, WebPage, BreadcrumbList) with language-stable IDs from a single identity source in [`site/config/meta.php`](./site/config/meta.php).
- **Feeds.** RSS and JSON feeds at `/feeds/rss` and `/feeds/json`, cached.
- **Images.** WebP thumbs with device-tuned srcsets, ThumbHash placeholders ([kirby-thumbhash](https://github.com/tobimori/kirby-thumbhash) + [unlazy](https://unlazy.byjohann.dev)) and on-the-fly transforms via the [thumb-router plugin](./site/plugins/thumb-router).
- **No-flash dark mode.** An inline pre-paint script sets `data-theme` before any CSS loads.

## Development

1. Create your `.env` from the example:

   ```bash
   cp .env.development.example .env
   ```

2. Install dependencies:

   ```bash
   pnpm install
   composer install
   ```

3. Start the frontend watchers (Vite + UnoCSS, via mprocs):

   ```bash
   pnpm run dev
   ```

4. Run the PHP server in a second shell – or use a dev server of your choice (e.g. Laravel Valet):

   ```bash
   composer start
   ```

Build the production assets with `pnpm run build`. Deployment runs through [`scripts/ploi-deploy.sh`](./scripts/ploi-deploy.sh) on [ploi.io](https://ploi.io).

Content is not part of the repository – [`scripts/sync-content.sh`](./scripts/sync-content.sh) rsyncs `storage/content` between local and server (dry-run by default, `--apply` to execute).

## License

[MIT](./LICENSE) License © 2021-PRESENT [Johann Schopplich](https://github.com/johannschopplich)
