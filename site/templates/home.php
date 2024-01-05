<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$root = dirname(__DIR__, 2);
$icons = array_map(
    fn ($file) => basename($file),
    glob($root . '/public/assets/icons/*.svg')
);
$filteredIcons = array_values(array_filter($icons, fn ($file) => !str_starts_with($file, '_')));
?>

<?php snippet('layouts/default', slots: true) ?>
  <div class="content relative max-w-screen-lg pt-8xl">
    <div class="relative">
      <div class="relative prose">
        <?php foreach ($page->text()->toBlocks() as $block): ?>
          <?php /** @var \Kirby\Cms\Block $block */ ?>
          <?php if ($block->type() === 'heading' && $block->content()->level()->value() === 'h1'): ?>
            <h1 class="headline" style="--du-decoration-offset: max(2px, 0.1em);">
              <?= $block->text() ?>
              <div
                class="relative inline-block h-[0.825em] select-none touch-manipulation [&>svg]:w-auto [&>svg]:h-full"
                style="--un-animated-duration: 800ms"
                data-sticker="svg"
              >
                <?= icon($filteredIcons[array_rand($filteredIcons)]) ?>
                <span class="absolute -inset-6 cursor-pointer"></span>
              </div>
            </h1>
          <?php else: ?>
            <div class="max-w-prose text-contrast-medium">
              <?= $block ?>
            </div>
          <?php endif ?>
        <?php endforeach ?>
      </div>
    </div>

    <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>
  </div>

  <div class="content max-w-screen-lg">
    <h2 class="title text-primary text-2xl text-center mb-lg">
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

      <h2 class="title text-primary text-2xl text-center mb-lg">
        <?= t('photography') ?>
      </h2>
    </div>

    <?php snippet('components/slider', compact('query'), slots: true) ?>
      <div class="swiper-slide pr-xs shrink-0 snap-end snap-always">
        <div class="h-$cell relative flex w-[min(65vw,25rem)] items-center justify-center border border-solid">
          <a href="<?= $query->first()->parent()->url() ?>" class="cta-button">
            <span class="absolute inset-0" aria-hidden="true"></span>
            <?= t('photography.morePhotos') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
          </a>
        </div>
      </div>
    <?php endsnippet() ?>

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

    <h2 class="title text-primary text-2xl text-center mb-lg">
      <?= t('contact') ?>
    </h2>

    <div class="border-t border-t-solid du-dark:border-contrast-lower pt-lg">
      <div class="prose max-w-prose text-contrast-medium">
        <?= $page->contactText()->toBlocks() ?>
        <div class="space-y-2">
          <?php foreach ($page->contact()->toStructure() as $item): ?>
            <div class="grid grid-cols-[auto_1fr] gap-xl md:grid-cols-3">
              <p><?= $item->title()->escape() ?></p>
              <p class="md:col-start-2 md:col-end-2"><?= $item->link()->permalinksToUrls() ?></p>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>
  </div>

  <div class="pb-8xl"></div>
<?php endsnippet() ?>
