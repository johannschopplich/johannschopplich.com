<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Collection $query */
?>
<?php snippet('header') ?>

<div class="container-lg section">
  <h1 class="sr-only"><?= $page->title() ?></h1>
  <h2 class="title font-size-2 sm:font-size-1 text-accent text-center mb-13">
    <?= t('articles.all') ?>
  </h2>

  <?php snippet('articles', ['query' => $query]) ?>
</div>

<?php snippet('pagination', ['pagination' => $query->pagination()]) ?>
<?php snippet('footer') ?>
