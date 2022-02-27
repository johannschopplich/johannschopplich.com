<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<?php snippet('intro', ['title' => $page->text()->escape()]) ?>

<div class="content max-w-screen-lg">
  <div class="">
    <h2 class="title text-2xl text-accent text-center mb-xl">
      <?= t('articles.latest') ?>
    </h2>

    <div class="overflow-hidden border-y">
      <?php snippet('articles', [
        'query' => $kirby->collection('articles')->paginate(4)
      ]) ?>
    </div>

    <div class="text-center mt-lg">
      <a href="<?= page('blog')->url() ?>" class="action-button">
        <?= t('articles.more') ?><span class="i-mdi-arrow-right ml-1" aria-hidden="true"></span>
      </a>
    </div>
  </div>
</div>

<?php if ($photography = page('photography')): ?>
  <?php $query = $photography
    ->gallery()
    ->toFiles()
    ->shuffle()
    ->filterBy('ratio', '>=', '1')
    ->limit(4)
  ?>

  <div class="content max-w-screen-lg">
    <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>

    <h2 class="title text-2xl text-accent text-center mb-xl">
      <?= t('photography') ?>
    </h2>
  </div>

  <?php snippet('photography/carousel', compact('query')) ?>

  <div class="content max-w-screen-lg pb-8xl">
    <div class="text-center mt-lg">
      <a href="<?= $photography->url() ?>" class="action-button">
        <?= t('photography.morePhotos') ?><span class="i-mdi-arrow-right ml-1" aria-hidden="true"></span>
      </a>
    </div>
  </div>
<?php endif ?>
