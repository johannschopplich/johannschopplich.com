<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-8xl md:pt-8xl">
  <div class="content">
    <div class="prose max-w-prose">
      <h1 class="sr-only">
        <?= $page->title()->escape() ?>
      </h1>

      <?php foreach ($page->text()->toBlocks() as $block): ?>
        <?php /** @var \Kirby\Cms\Block $block */ ?>
        <?php if ($block->type() === 'gallery'): ?>
    </div>
  </div>
  <div class="mt-$un-prose-spacer mb-8xl">
    <?= $block ?>
  </div>
  <div class="content">
    <div class="prose max-w-prose">
    <?php else: ?>
      <?= $block ?>
    <?php endif ?>
  <?php endforeach ?>
    </div>
  </div>
  <?php endsnippet() ?>