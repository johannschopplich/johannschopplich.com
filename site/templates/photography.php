<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<div class="py-8xl">
  <?php $blocks = $page->text()->toBlocks() ?>
  <?php if ($blocks->isNotEmpty()): ?>
    <div class="content pb-5xl max-w-prose">
      <section class="prose">
        <h1 class="sr-only">
          <?= $page->title()->escape() ?>
        </h1>

        <?= $blocks ?>
      </section>
    </div>
  <?php endif ?>

  <?php snippet('shortcuts/masonry', ['query' => $page->gallery()->toFiles()]) ?>
</div>
