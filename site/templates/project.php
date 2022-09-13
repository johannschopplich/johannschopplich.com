<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<div class="pt-8xl pb-5xl">
  <div class="content pb-5xl max-w-prose">
    <p class="text-contrast-medium">
      <?= $page->subtitle()->escape() ?>
    </p>

    <h1 class="title font-900 text-3xl md:text-4xl">
      <?= $page->title()->escape() ?>
    </h1>

    <div class="mt-xs">
      <?= asset('assets/img/article-spacer.svg')->read() ?>
    </div>
  </div>

  <?php snippet('shortcuts/slider', [
    'query' => $page->gallery()->toFiles(),
    'zoomable' => true
  ]) ?>
</div>

<div class="pb-8xl">
  <div class="content max-w-prose">
    <?php if ($page->text()->isNotEmpty()): ?>
      <div class="prose mb-5xl">
        <?= $page->text()->toBlocks() ?>
      </div>
    <?php endif ?>

    <div class="border-t pt-lg">
      <div class="-ml-1">
        <a href="<?= $page->parent()->url() ?>" class="action-button">
          <span class="i-bx-left-arrow-alt mr-1" aria-hidden="true"></span><?= t('project.allWorks') ?>
        </a>
      </div>
    </div>
  </div>
</div>
