<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
?>

<?php snippet('layouts/default', slots: true) ?>
  <div class="py-8xl">
    <div class="content max-w-screen-lg pb-5xl">
      <h1 class="headline mb-5xl">
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
