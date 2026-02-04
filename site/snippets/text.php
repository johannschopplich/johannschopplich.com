<?php

/** @var \Kirby\Cms\Page $page */
/** @var string|null $textLanguageCode */
/** @var \Kirby\Cms\Blocks $blocks */

$sectionAttrs = attr([
  'class' => 'content',
  'lang' => $textLanguageCode
]);
?>

<section class="pt-5xl pb-8xl md:pt-8xl">
  <div class="content">
    <div class="max-w-prose">
      <h1 class="headline">
        <?= $page->title()->escape() ?>
      </h1>
    </div>
  </div>

  <div class="content-inset-l pb-5xl">
    <?php if ($page->published()->isNotEmpty()): ?>
      <div class="flex gap-2 items-center mt-1 after:content-empty after:flex-1 after:h-[1px] after:bg-contrast-low after:content-offset-r un-dark:after:bg-contrast-lower">
        <p class="subtext text-contrast-soft shrink-0 max-md:text-sm">
          <?= t('article.publishedAt') ?>
          <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
            <?= $page->published()->toDate(dateFormatter()) ?>
          </time>
        </p>
      </div>
    <?php endif ?>
  </div>

  <div <?= $sectionAttrs ?>>
    <div class="prose max-w-prose">
      <?php foreach ($blocks as $block): ?>
        <?php /** @var \Kirby\Cms\Block $block */ ?>
        <?php if ($block->type() === 'gallery'): ?>
    </div>
  </div>
  <div class="mt-$un-prose-space-y">
    <?= $block ?>
  </div>
  <div <?= $sectionAttrs ?>>
    <div class="prose max-w-prose">
    <?php else: ?>
      <?= $block ?>
    <?php endif ?>
  <?php endforeach ?>

  <?php if ($page->parent()?->intendedTemplate()?->name() === 'articles'): ?>
    <?= $page->parent()->articleFooter()->toBlocks() ?>
  <?php endif ?>
    </div>
</section>