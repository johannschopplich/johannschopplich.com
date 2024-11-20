<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-8xl md:pt-8xl">
  <div class="content max-w-screen-lg pb-5xl">
    <h1 class="headline mb-5xl">
      <?= t('articles.all') ?>
    </h1>
  </div>

  <div class="border-y border-y-solid un-dark:border-contrast-lower">
    <div class="content max-w-screen-lg">
      <div class="overflow-hidden">
        <?php snippet('articles', ['query' => $query]) ?>
      </div>
    </div>
  </div>


  <div class="content max-w-screen-lg pt-5xl">
    <?php snippet('pagination', ['pagination' => $query->pagination()]) ?>
  </div>
</div>
<?php endsnippet() ?>