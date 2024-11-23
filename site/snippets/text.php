<?php

/** @var \Kirby\Cms\Page $page */
/** @var string|null $textLanguageCode */
/** @var \Kirby\Cms\Blocks $blocks */

$sectionAttrs = attr([
  'class' => 'content prose max-w-prose is-article',
  'lang' => $textLanguageCode
]);
?>

<section class="pt-5xl pb-8xl md:pt-8xl">
  <div class="content max-w-prose">
    <h1 class="headline">
      <?= $page->title()->escape() ?>
    </h1>
  </div>

  <div class="content-l pb-5xl">
    <?php if ($page->published()->isNotEmpty()): ?>
      <div class="flex gap-1 items-center mt-1 after:content-empty after:flex-1 after:h-[1px] after:bg-current un-dark:after:bg-contrast-lower">
        <p class="shrink-0 text-sm font-500">
          <?= t('article.publishedAt') ?>
          <time datetime="<?= $page->published()->toDate('Y-MM-dd') ?>">
            <?= $page->published()->toDate(dateFormatter()) ?>
          </time>
        </p>
      </div>
    <?php endif ?>
  </div>

  <div <?= $sectionAttrs ?>>
    <?php foreach ($blocks as $block): ?>
      <?php /** @var \Kirby\Cms\Block $block */ ?>
      <?php if ($block->type() === 'gallery'): ?>
  </div>
  <div class="mt-[--un-prose-spacer]">
    <?= $block ?>
  </div>
  <div <?= $sectionAttrs ?>>
  <?php else: ?>
    <?= $block ?>
  <?php endif ?>
<?php endforeach ?>

<?php if ($page->parent()?->intendedTemplate()?->name() === 'articles'): ?>
  <?= $page->parent()->articleFooter()->toBlocks() ?>
<?php endif ?>
  </div>
</section>