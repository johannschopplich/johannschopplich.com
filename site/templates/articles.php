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

    <div class="relative">
      <div class="overflow-hidden border-y border-y-solid un-dark:border-contrast-lower">
        <?php snippet('articles', ['query' => $query]) ?>
      </div>

      <?php foreach (['top-0', 'bottom-0'] as $position): ?>
        <div class="pointer-events-none absolute <?= $position ?> left-0 content-empty w-screen h-px bg-current hidden lg:block un-dark:bg-contrast-lower"></div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="content max-w-screen-lg">
    <?php snippet('pagination', ['pagination' => $query->pagination()]) ?>
  </div>
</div>
<?php endsnippet() ?>