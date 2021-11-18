<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<!DOCTYPE html>
<html class="due-var-color-primary due-var-color-accent" lang="<?= $kirby->languageCode() ?>">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $page->customTitle()->or($page->title() . ' – ' . $site->title()) ?></title>

  <?php $meta = $page->meta() ?>
  <?= $meta->robots() ?>
  <?= $meta->jsonld() ?>
  <?= $meta->social() ?>

  <meta name="theme-color" content="#c9787c">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="<?= $site->title()->escape() ?>">

  <link rel="manifest" href="/manifest.json">
  <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png" sizes="180x180">
  <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">

  <?php foreach ($kirby->languages() as $language): ?>
    <?php if ($language->code() === $kirby->languageCode()) continue; ?>
    <link rel="alternate" hreflang="<?= $language->code() ?>" href="<?= $page->urlForLanguage($language->code()) ?>">
  <?php endforeach ?>

  <link rel="alternate" type="application/rss+xml" title="<?= $site->title()->escape() ?>" href="<?= url('feeds/rss') ?>">
  <link rel="alternate" type="application/json" title="<?= $site->title()->escape() ?>" href="<?= url('feeds/json') ?>">

  <?= vite()->js() ?>
  <?= vite()->css() ?>

  <?php if (vite()->isDev()): ?>
    <?= css('assets/dev/index.css?v=' . time(), ['id' => 'vite-dev-css']) ?>
  <?php endif ?>

  <?= css([
    'assets/fonts/Inter.css',
    'assets/fonts/UxumGrotesque.css'
  ]) ?>

  <link rel="preload" href="/assets/fonts/UxumGrotesque-Bold.woff2" as="font" type="font/woff2" crossorigin>
  <!-- <link rel="preload" href="/assets/fonts/UxumGrotesque-Medium.woff2" as="font" type="font/woff2" crossorigin> -->

  <script><?= asset('dist/assets/inline-head.js')->read() ?></script>

  <script async defer data-domain="johannschopplich.com" src="https://plausible.io/js/plausible.js"></script>

</head>

<body data-template="<?= $page->intendedTemplate()->name() ?>">
  <header class="container-fluid due-pt-m">
    <?php snippet('navigation') ?>
  </header>

  <main>
