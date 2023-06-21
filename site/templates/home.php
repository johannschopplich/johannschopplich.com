<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
  <div class="content relative max-w-screen-lg pt-8xl">
    <div class="relative">
      <div class="relative prose">
        <?php foreach ($page->text()->toBlocks() as $block): ?>
          <?php /** @var \Kirby\Cms\Block $block */ ?>
          <?php if ($block->type() === 'heading' && $block->content()->level()->value() === 'h1'): ?>
            <h1 class="headline">
              <?= $block->text() ?>
              <?php snippet('components/sticker', ['emoji' => '🌻']) ?>
            </h1>
          <?php else: ?>
            <div class="max-w-prose">
              <?= $block ?>
            </div>
          <?php endif ?>
        <?php endforeach ?>
      </div>
    </div>

    <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>
  </div>

  <div class="content max-w-screen-lg">
    <h2 class="title text-primary mb-lg text-center text-2xl">
      <?= t('articles.latest') ?>
    </h2>

    <div class="overflow-hidden border-y border-y-solid du-dark:border-contrast-lower">
      <?php snippet('articles', [
        'query' => $kirby->collection('articles')->paginate(4)
      ]) ?>
    </div>

    <div class="text-center mt-lg">
      <a href="<?= page('blog')->url() ?>" class="cta-button">
        <?= t('articles.more') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
      </a>
    </div>
  </div>

  <?php if ($photography = page('photography')): ?>
    <?php $query = $photography
      ->gallery()
      ->toFiles()
      ->shuffle()
      // ->filterBy('ratio', '>=', '1')
      ->limit(4)
    ?>

    <div class="content max-w-screen-lg">
      <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>

      <h2 class="title text-primary mb-lg text-center text-2xl">
        <?= t('photography') ?>
      </h2>
    </div>

    <?php snippet('components/slider', compact('query')) ?>

    <div class="content max-w-screen-lg">
      <div class="text-center mt-lg">
        <a href="<?= $photography->url() ?>" class="cta-button">
          <?= t('photography.morePhotos') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
        </a>
      </div>
    </div>
  <?php endif ?>

  <div class="content max-w-screen-lg">
    <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>

    <h2 class="title text-primary mb-lg text-center text-2xl">
      <?= t('contact') ?>
    </h2>

    <div class="border-t border-t-solid du-dark:border-contrast-lower pt-lg">
      <div class="max-w-prose prose">
        <?= $page->contactText()->toBlocks() ?>
        <div class="space-y-2">
          <?php foreach ($page->contact()->toStructure() as $item): ?>
            <div class="grid grid-cols-3">
              <p class="text-contrast-medium"><?= $item->title()->escape() ?></p>
              <p class="col-start-2 col-end-2"><?= $item->link() ?></p>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>

  <div class="pb-8xl"></div>
<?php endsnippet() ?>
