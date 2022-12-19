<?php

/** @var \Kirby\Cms\Page $page */ ?>
<div class="py-8xl">
  <div class="content pb-5xl max-w-prose">
    <?php if ($page->published()->exists() && $page->published()->isNotEmpty()): ?>
      <p class="text-contrast-medium">
        <?= t('article.publishedAt') ?>
        <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
          <?= $page->published()->toDate(dateFormatter()) ?>
        </time>
      </p>
    <?php endif ?>

    <h1 class="title font-900 text-3xl md:text-4xl">
      <?= $page->title()->escape() ?>
    </h1>

    <div class="mt-xs">
      <?= asset('assets/img/article-spacer.svg')->read() ?>
    </div>
  </div>

  <div class="content max-w-prose">
    <section class="prose"<?= attr(['lang' => $textLanguageCode], ' ') ?>>
      <?= $text ?>

      <?php if ($page->parent()?->template()?->name() === 'articles'): ?>
        <?= $page->parent()->articleFooter()->toBlocks() ?>
      <?php endif ?>
    </section>
  </div>
</div>
