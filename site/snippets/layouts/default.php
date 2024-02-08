<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<!--
https://github.com/johannschopplich/johannschopplich.com
-->
<!DOCTYPE html>
<html
  class="var-color-primary var-color-primary-500 var-color-primary-200"
  data-theme="light"
  lang="<?= $kirby->languageCode() ?>"
>
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $page->customTitle()->or($page->title() . ' – ' . $site->title()) ?></title>

  <?php $meta = $page->meta() ?>
  <?= $meta->robots() ?>
  <?= $meta->jsonld() ?>
  <?= $meta->social() ?>

  <meta name="theme-color" content="#fefcf8">
  <link rel="icon" href="/favicon.ico" sizes="32x32">
  <link rel="icon" href="/assets/icon.svg" type="image/svg+xml">
  <link rel="apple-touch-icon" href="/assets/apple-touch-icon.png">
  <link rel="manifest" href="/app.webmanifest">

  <?php foreach ($kirby->languages() as $language): ?>
    <?php if ($language->code() === $kirby->languageCode()) continue ?>
    <link rel="alternate" hreflang="<?= $language->code() ?>" href="<?= $page->urlForLanguage($language->code()) ?>">
  <?php endforeach ?>

  <link rel="alternate" type="application/rss+xml" title="<?= $site->title()->escape() ?>" href="<?= url('feeds/rss') ?>">
  <link rel="alternate" type="application/json" title="<?= $site->title()->escape() ?>" href="<?= url('feeds/json') ?>">

  <?php if (vite()->isDev()): ?>
    <?= css('assets/dev/index.css?v=' . time(), ['id' => 'vite-dev-css']) ?>
  <?php endif ?>

  <?= vite()->js('main.ts') ?>
  <?= vite()->css('main.ts') ?>

  <?= css([
    'assets/fonts/CooperHewitt.css',
    'assets/fonts/IosevkaAile.css'
  ]) ?>
  <link rel="preload" href="/assets/fonts/woff2/CooperHewitt-Heavy.woff2" as="font" type="font/woff2" crossorigin>

  <script>
    <?php $srcDir = vite()->isDev() ? '../src' : 'dist/assets' ?>
    <?= asset($srcDir . '/head.js')->read() ?>
  </script>

  <script defer data-domain="johannschopplich.com" src="https://plausible.io/js/plausible.js"></script>

</head>

<body class="min-h-[100svh]" data-template="<?= $page->intendedTemplate()->name() ?>">

  <header
    class="content sticky top-0 border-b border-b-solid z-20 py-2 md:py-xs bg-white bg-opacity-40 bg-image-$gradient bg-[length:4px_4px] backdrop-blur-[8px] du-dark:border-contrast-lower du-dark:bg-zinc-900 du-dark:bg-opacity-40"
    style="--gradient: radial-gradient(rgba(255, 255, 255, 0) 1px, var(--du-color-background) 1px)"
  >
    <?php snippet('navigation') ?>
  </header>

  <main class="relative">
    <?= $slot ?>
  </main>

  <?php snippet('footer') ?>

<?php /*: ?>
<is-land on:media="(prefers-reduced-motion: no-preference)">
	<snow-fall count="25" style="--snow-fall-color: var(--du-color-contrast-low)"></snow-fall>
</is-land>
<?php */ ?>

<?php snippet('site-data') ?>

</body>
</html>
