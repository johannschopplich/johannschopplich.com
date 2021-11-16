<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<?php snippet('intro', ['title' => $page->text()->kti()]) ?>

<div class="container-lg pb-13">
  <h2 class="title font-size-2 sm:font-size-1 text-accent text-center mb-13">
    <?= t('articles.latest') ?>
  </h2>

  <?php snippet('articles', [
    'query' => $kirby->collection('articles')->paginate(4)
  ]) ?>

  <div class="text-center mt-13">
    <a href="<?= page('blog')->url() ?>" class="button-text px-3">
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
  <div class="section-divider mt-2" data-animere="GrowSectionDivider"></div>

  <div class="section-photography pb-13">
    <h2 class="title font-size-2 sm:font-size-1 text-accent text-center mb-13">
      <?= t('photography') ?>
    </h2>

    <?php snippet('photography/carousel', compact('query')) ?>

    <div class="text-center mt-5">
      <a href="<?= $photography->url() ?>" class="button-text px-3">
        <?= t('photography.morePhotos') ?>
      </a>
    </div>
  </div>
<?php endif ?>

<?php snippet('footer') ?>
