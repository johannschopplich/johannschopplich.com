<?php

/** @var \Kirby\Cms\Page $page */ ?>
<div class="py-8xl">
  <div class="content max-w-prose pb-5xl">
    <?php if ($page->published()->isNotEmpty()): ?>
      <p class="text-contrast-medium mb-1">
        <?= t('article.publishedAt') ?>
        <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
          <?= $page->published()->toDate(dateFormatter()) ?>
        </time>
      </p>
    <?php endif ?>

    <h1 class="title font-900 text-3xl md:text-4xl">
      <?= $page->title()->escape() ?>
    </h1>

    <?php /*
    <div class="mt-xs">
      <?= asset('assets/img/article-spacer.svg')->read() ?>
    </div>
    */ ?>
  </div>

  <div class="content max-w-prose">
    <section <?= attr([
      'class' => 'prose is-article',
      'lang' => $textLanguageCode
    ]) ?>>
      <?= $text ?>

      <?php if ($page->parent()?->intendedTemplate()?->name() === 'articles'): ?>
        <?= $page->parent()->articleFooter()->toBlocks() ?>
      <?php endif ?>
    </section>
  </div>
</div>
