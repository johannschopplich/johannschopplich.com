<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<!--
https://github.com/johannschopplich/johannschopplich.com
-->
<!doctype html>
<html
  class="var-color-primary var-color-primary-400 var-color-primary-500 var-color-primary-600 var-color-primary-200"
  data-theme="light"
  lang="<?= $kirby->languageCode() ?>">

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
    <?= vite()->isDev()
      ? asset('src/inline/theme-init.js')->read()
      : asset('dist/inline/theme-init.js')->read() ?>
  </script>

</head>

<body class="min-h-[100svh] md:pt-sm" data-template="<?= $page->intendedTemplate()->name() ?>">

  <header
    class="sticky -top-px border-b border-b-transparent border-b-solid transition-border-color z-20
           bg-[color-mix(in_srgb,var(--un-color-background)50%,transparent)] bg-[image:radial-gradient(rgba(255,255,255,0)_1px,_var(--un-color-background)_1px)] bg-[length:4px_4px] backdrop-blur-[8px]
           [&.is-pinned]:border-contrast-lower un-dark:[&.is-pinned]:border-contrast-lowest">
    <div class="content py-2 md:py-xs">
      <?php snippet('navigation') ?>
    </div>
  </header>

  <main class="relative">
    <?= $slot ?>
  </main>

  <?php snippet('footer') ?>

</body>

</html>