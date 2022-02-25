<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
layout()
?>

<div class="py-8xl">
  <div class="content max-w-screen-lg pb-5xl">
    <h1 class="text-4xl text-accent font-heading font-900 mb-5xl">
      <?= t('articles.all') ?>
    </h1>

    <div class="overflow-hidden border-y">
      <?php snippet('articles', ['query' => $query]) ?>
    </div>
  </div>

  <div class="content max-w-screen-lg">
    <?php snippet('pagination', ['pagination' => $query->pagination()]) ?>
  </div>
</div>
