<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<!--
https://github.com/johannschopplich/johannschopplich.com
-->
<!doctype html>
<html data-theme="light" lang="<?= $kirby->languageCode() ?>">

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
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">

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
      ? asset('../src/inline/theme-init.js')->read()
      : asset('dist/inline/theme-init.js')->read() ?>
  </script>

</head>

<body class="min-h-[100svh] md:pt-sm" data-template="<?= $page->intendedTemplate()->name() ?>">

  <header class="
    sticky -top-px pt-px z-20
    border-b border-b-transparent border-b-solid transition-border-color
    bg-theme-background/75 bg-[image:radial-gradient(rgba(255,255,255,0)_1px,_var(--un-color-background)_1px)] bg-[length:3px_3px] backdrop-blur-[8px]
    [&.is-pinned]:border-contrast-lower dark:[&.is-pinned]:border-contrast-lowest
  ">
    <div class="content py-xs">
      <?php snippet('partials/navigation') ?>
    </div>
  </header>

  <main class="relative">
    <?= $slot ?>
  </main>

  <?php snippet('partials/footer') ?>

</body>

</html>
