<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
layout()
?>

<div class="container-lg section">
  <h1 class="sr-only"><?= $page->title() ?></h1>
  <h2 class="title due-text-2 sm:due-text-1 text-accent text-center due-mb-xl">
    <?= t('articles.all') ?>
  </h2>

  <?php snippet('articles', ['query' => $query]) ?>
</div>

<?php snippet('pagination', ['pagination' => $query->pagination()]) ?>
