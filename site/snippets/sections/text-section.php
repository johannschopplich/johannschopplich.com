<?php

/** @var \Kirby\Cms\Page $page */
/** @var string|null $lang */
/** @var \Kirby\Cms\Blocks $blocks */

if ($page->parent()?->intendedTemplate()?->name() === 'articles') {
    $blocks = $blocks->add($page->parent()->articleFooter()->toBlocks());
}
?>

<section class="pt-5xl pb-8xl md:pt-8xl">
  <div class="px-lg md:px-gutter">
    <div class="max-w-prose">
      <h1 class="headline">
        <?= $page->title()->escape() ?>
      </h1>
    </div>
  </div>

  <div class="pl-lg pb-5xl md:pl-gutter">
    <?php if ($page->published()->isNotEmpty()): ?>
      <div class="flex gap-2 items-center mt-1 after:content-empty after:flex-1 after:h-[1px] after:border-b after:border-dotted after:border-contrast-soft after:mr-lg after:md:mr-gutter">
        <p class="subtext text-contrast-soft shrink-0 max-md:text-sm">
          <?= t('article.publishedAt') ?>
          <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
            <?= $page->published()->toDate(dateFormatter()) ?>
          </time>
        </p>
      </div>
    <?php endif ?>
  </div>

  <?php snippet('components/prose-blocks', compact('blocks', 'lang')) ?>
</section>
