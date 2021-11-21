<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<?php snippet('intro', ['title' => $page->text()->kti()]) ?>

<div class="due-container-lg due-pb-xl">
  <h2 class="title due-text-2 sm:due-text-1 text-accent text-center due-mb-xl">
    <?= t('articles.latest') ?>
  </h2>

  <?php snippet('articles', [
    'query' => $kirby->collection('articles')->paginate(4)
  ]) ?>

  <div class="text-center due-mt-xl">
    <a href="<?= page('blog')->url() ?>" class="button-text">
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
  <div class="section-divider due-mt-xs" data-animere="GrowSectionDivider"></div>

  <div class="section-photography due-pb-xl">
    <h2 class="title due-text-2 sm:due-text-1 text-accent text-center due-mb-xl">
      <?= t('photography') ?>
    </h2>

    <?php snippet('photography/carousel', compact('query')) ?>

    <div class="text-center due-mt-m">
      <a href="<?= $photography->url() ?>" class="button-text">
        <?= t('photography.morePhotos') ?>
      </a>
    </div>
  </div>
<?php endif ?>
