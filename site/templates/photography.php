<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<div class="due-py-xl">
  <?php $blocks = $page->text()->toBlocks() ?>
  <?php if ($blocks->isNotEmpty()): ?>
    <div class="container-fluid max-w-[60ch] due-pb-xl">
      <section class="content">
        <h1 class="sr-only">
          <?= $page->title()->escape() ?>
        </h1>
        <?= $blocks ?>
      </section>
    </div>
  <?php endif ?>

  <?php snippet('photography/masonry', ['query' => $page->gallery()->toFiles()]) ?>
</div>
