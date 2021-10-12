<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<?php snippet('intro', ['title' => $page->text()->kti()]) ?>

<div class="container is-lg pb-xl">
  <h2 class="title text-2 sm:text-1 text-accent text-center mb-xl">
    <?= t('articles.latest') ?>
  </h2>

  <?php snippet('articles', [
    'query' => $kirby->collection('articles')->paginate(4)
  ]) ?>

  <div class="text-center mt-xl">
    <a href="<?= page('blog')->url() ?>" class="button is-text px-s">
      <?= t('articles.more') ?>
    </a>
  </div>
</div>

<?php snippet('popular', [
  'query' => $kirby->collection('popularArticles'),
  'heading' => t('articles.popular')
]) ?>

<?php if ($photography = page('photography')): ?>
  <?php $query = $photography
    ->gallery()
    ->toFiles()
    ->shuffle()
    ->filterBy('ratio', '>=', '1')
    ->limit(4)
  ?>
  <div class="section-divider mt-xs" data-animere="GrowSectionDivider"></div>

  <div class="section-photography pb-xl">
    <h2 class="title text-2 sm:text-1 text-accent text-center mb-xl">
      <?= t('photography') ?>
    </h2>

    <?php snippet('photography/carousel', compact('query')) ?>

    <div class="text-center mt-m">
      <a href="<?= $photography->url() ?>" class="button is-text px-s">
        <?= t('photography.morePhotos') ?>
      </a>
    </div>
  </div>
<?php endif ?>

<?php snippet('footer') ?>
