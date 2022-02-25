<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<div class="py-8xl">
  <?php $blocks = $page->text()->toBlocks() ?>
  <?php if ($blocks->isNotEmpty()): ?>
    <div class="content max-w-prose pb-5xl">
      <section class="prose font-heading font-500">
        <h1 class="sr-only">
          <?= $page->title()->escape() ?>
        </h1>

        <?= $blocks ?>
      </section>
    </div>
  <?php endif ?>

  <?php snippet('photography/masonry', ['query' => $page->gallery()->toFiles()]) ?>
</div>
