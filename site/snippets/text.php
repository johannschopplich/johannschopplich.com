<?php

/** @var \Kirby\Cms\Page $page */ ?>
<div class="pt-5xl pb-8xl md:pt-8xl">
  <div class="content max-w-prose pb-5xl">
    <h1 class="headline">
      <?= $page->title()->escape() ?>
    </h1>

    <?php if ($page->published()->isNotEmpty()): ?>
      <div class="flex gap-1 items-center after:content-empty after:flex-1 after:h-[1px] after:bg-contrast-medium">
        <p class="shrink-0 text-sm font-500 text-contrast-medium">
          <?= t('article.publishedAt') ?>
          <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
            <?= $page->published()->toDate(dateFormatter()) ?>
          </time>
        </p>
      </div>
    <?php endif ?>
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
