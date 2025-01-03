<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-8xl md:pt-8xl">
  <div class="content pb-8xl">
    <div class="max-w-screen-lg">
      <h1 class="headline">
        <?= t('articles.all') ?>
      </h1>
    </div>
  </div>

  <div class="border-y border-y-solid un-dark:border-contrast-lower">
    <div class="content">
      <div class="max-w-screen-lg overflow-hidden">
        <?php snippet('articles', ['query' => $query]) ?>
      </div>
    </div>
  </div>

  <div class="content pt-5xl">
    <div class="max-w-screen-lg">
      <?php snippet('pagination', ['pagination' => $query->pagination()]) ?>
    </div>
  </div>
</div>
<?php endsnippet() ?>