<?php

/** @var \Kirby\Cms\Page $page */ ?>
<div class="py-8xl">
  <div class="content max-w-prose pb-5xl">
    <?php if ($page->published()->exists() && $page->published()->isNotEmpty()) : ?>
      <p class="text-contrast-medium">
        <?= t('article.publishedAt') ?>
        <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
          <?= $page->published()->toDate(new IntlDateFormatter($kirby->languageCode(), IntlDateFormatter::LONG, IntlDateFormatter::NONE)) ?>
        </time>
      </p>
    <?php endif ?>

    <h1 class="title text-3xl leading-heading font-900 md:text-4xl">
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
