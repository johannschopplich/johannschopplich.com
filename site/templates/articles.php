<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-8xl md:pt-8xl">
  <div class="px-lg md:px-gutter">
    <div class="max-w-screen-lg">
      <h1 class="headline">
        <?= t('articles.all') ?>
      </h1>
    </div>
  </div>

  <div class="section-divider"></div>

  <div class="border-y border-y-solid border-contrast-low dark:border-contrast-lower">
    <div class="px-lg md:px-gutter">
      <div class="max-w-screen-lg overflow-hidden">
        <?php snippet('components/article-grid', ['query' => $query]) ?>
      </div>
    </div>
  </div>

  <div class="px-lg pt-5xl md:px-gutter">
    <div class="max-w-screen-lg">
      <?php snippet('components/pagination', ['pagination' => $query->pagination()]) ?>
    </div>
  </div>
</div>
<?php endsnippet() ?>
