<?php

/** @var \Kirby\Cms\Page $page */ ?>
<div class="py-8xl">
  <div class="content max-w-prose pb-5xl">
    <?php if ($page->published()->exists() && $page->published()->isNotEmpty()) : ?>
      <p class="text-contrast-medium">
        <?php $format = $kirby->languageCode() === 'de' ? '%e. %B %Y' : '%e %B %Y' ?>
        <?= t('article.publishedAt') ?>
        <time datetime="<?= $page->published()->toDate('%Y-%m-%d') ?>">
          <?= $page->published()->toDate($format) ?>
        </time>
      </p>
    <?php endif ?>

    <h1 class="title text-3xl md:text-4xl font-900">
      <?= $page->title()->escape() ?>
    </h1>

    <div class="mt-xs">
      <?= asset('assets/img/article-spacer.svg')->read() ?>
    </div>
  </div>

  <div class="content max-w-prose">
    <section class="prose" <?= attr(['lang' => $textLanguageCode], ' ') ?>>
      <?= $text ?>

      <?php if (
        $page->parent() !== null &&
        $page->parent()->template()->name() === 'articles'
      ) : ?>
        <?= $page->parent()->articleFooter()->toBlocks() ?>
      <?php endif ?>
    </section>
  </div>
</div>
