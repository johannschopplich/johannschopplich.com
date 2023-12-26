<?php

/** @var \Kirby\Cms\Page $page */ ?>
<div class="py-8xl">
  <div class="content max-w-prose pb-5xl">
    <h1 class="headline">
      <?= $page->title()->escape() ?>
    </h1>

    <?php if ($page->published()->isNotEmpty()): ?>
      <div class="flex items-center mt-2">
        <?= asset('assets/article-spacer.svg')->read() ?>
          <p class="text-background-shadow absolute text-sm font-500 text-contrast-medium">
            <?= t('article.publishedAt') ?>
            <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
              <?= $page->published()->toDate(dateFormatter()) ?>
            </time>
          </p>
        </div>
      </div>
    <?php endif ?>

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
