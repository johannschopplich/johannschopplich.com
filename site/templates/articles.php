<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
snippet('layouts/default', slots: true);
?>

<div class="py-8xl">
  <div class="content pb-5xl max-w-screen-lg">
    <h1 class="text-primary font-heading font-900 mb-5xl text-3xl md:text-4xl">
      <?= t('articles.all') ?>
    </h1>

    <div class="overflow-hidden border-y border-y-solid du-dark:border-contrast-lower">
      <?php snippet('articles', ['query' => $query]) ?>
    </div>
  </div>

  <div class="content max-w-screen-lg">
    <?php snippet('pagination', ['pagination' => $query->pagination()]) ?>
  </div>
</div>

<?php endsnippet() ?>
