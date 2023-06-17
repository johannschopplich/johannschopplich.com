<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
  <div class="content relative max-w-screen-lg pt-8xl">
    <div class="relative">
      <?php /*
      <div class="absolute inset-x-0 -top-5xl bottom-0">
        <div class="h-full flex max-w-screen-lg justify-end">
          <svg width="0" height="0">
            <filter id="grainy-blur" x="-150%" y="-150%" width="400%" height="400%">
              <feGaussianBlur stdDeviation="39" result="blur"></feGaussianBlur>
              <feTurbulence type="fractalNoise" baseFrequency=".537"></feTurbulence>
              <feComposite in="blur"></feComposite>
              <feComposite in="blur" operator="in"></feComposite>
            </filter>
          </svg>
          <div
            class="h-[75%] aspect-[1/1] rounded-1/2 md:h-full"
            style="background: conic-gradient(#f0d590, var(--du-color-primary-200), #f0d590 75%); filter: url(#grainy-blur);"
          ></div>
        </div>
      </div>
      */ ?>

      <div class="relative prose">
        <?php foreach ($page->text()->toBlocks() as $block): ?>
          <?php /** @var \Kirby\Cms\Block $block */ ?>
          <?php if ($block->type() === 'heading' && $block->content()->level()->value() === 'h1'): ?>
            <h1 class="title font-900 text-size-2xl leading-heading md:text-size-4xl">
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
      <a href="<?= page('blog')->url() ?>" class="action-button">
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
        <a href="<?= $photography->url() ?>" class="action-button">
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
