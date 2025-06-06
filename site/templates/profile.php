<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-5xl md:pt-8xl md:pb-8xl">
  <div class="grid max-w-screen-xl gap-5xl sm:gap-6xl sm:pr-lg md:grid-cols-2 md:pr-3xl md:px-[max(4vw,1.875rem)]">
    <?php if ($image = $page->thumbnail()->toFile()): ?>
      <div>
        <figure class="relative unselectable md:shadow-[var(--un-frame-shadow-template)_var(--un-color-border)]">
          <svg id="drauu-canvas" class="absolute top-0 left-0 z-10 hidden h-full w-full cursor-crosshair touch-pinch-zoom md:block"></svg>
          <img
            srcset="<?= $image->srcset() ?>"
            sizes="(min-width: 1280px) 640px, (min-width: 640px) 50vw, 100vw"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            alt="<?= $image->alt() ?>">
        </figure>

        <div class="drauu-controls mt-xs ml-[4px] hidden flex-wrap items-center gap-1 md:flex">
          <button id="m-stylus" class="is-active" title="<?= t('drauu.stylus') ?>">✍️</button>
          <button id="m-draw" title="<?= t('drauu.draw') ?>">✏️</button>
          <!-- <button id="m-eraser" title="<?= t('drauu.eraser') ?>">⚪️</button> -->
          <button id="m-line" title="<?= t('drauu.line') ?>">⁄</button>
          <select id="size" title="<?= t('drauu.size') ?>">
            <?php foreach (range(1, 9) as $number): ?>
              <option value="<?= $number ?>" <?php e($number === 3, ' selected') ?>><?= $number ?></option>
            <?php endforeach ?>
          </select>
          <button id="clear" class="ml-auto" title="<?= t('drauu.clear') ?>">🗑</button>
          <button id="download" title="Download">📥</button>
        </div>
      </div>
    <?php endif ?>

    <div class="prose px-lg md:px-0 xl:flex xl:flex-col xl:pb-[2.25rem]">
      <?php foreach ($page->bio()->toBlocks() as $block): ?>
        <?php /** @var \Kirby\Cms\Block $block */ ?>
        <?php if ($block->type() === 'heading'): ?>
          <h1 class="headline xl:mb-auto"><?= $block->text() ?></h1>
        <?php else: ?>
          <?= $block ?>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>
</div>

<div class="pb-8xl border-t border-t-solid border-contrast-low un-dark:border-contrast-lower">
  <div class="content pt-5xl md:pt-8xl">
    <div class="max-w-screen-xl">
      <?php foreach ($page->cv()->toLayouts() as $layout): ?>
        <div
          class="gap-x-3xl grid grid-cols-[repeat(auto-fit,minmax(calc(22ch-1.875rem),1fr))] gap-y-8 md:grid-cols-[repeat(3,minmax(0,auto))]"
          style="--un-prose-space-y: 1.25">
          <?php foreach ($layout->columns() as $column): ?>
            <div class="prose">
              <?php foreach ($column->blocks() as $block): ?>
                <?php /** @var \Kirby\Cms\Block $block */ ?>
                <?php if ($block->type() === 'heading'): ?>
                  <h2 class="text-sm uppercase tracking-[0.125ch] un-dark:text-contrast-medium"><?= $block->text() ?></h2>
                <?php elseif ($block->type() === 'text'): ?>
                  <?= preg_replace(
                    '/<code>(.*?)<\/code>/',
                    '<sparkly-text style="--sparkly-text-size: 2em; --sparkly-text-color: gold">$1</sparkly-text>',
                    $block->toHtml()
                  ) ?>
                <?php else: ?>
                  <?= $block ?>
                <?php endif ?>
              <?php endforeach ?>
            </div>
          <?php endforeach ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</div>
<?php endsnippet() ?>