<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<div class="section-bio md:pt-5xl">
  <div class="max-w-screen-lg pb-5xl grid gap-5xl sm:grid-cols-2 sm:gap-6xl sm:pr-lg md:pr-3xl">
    <?php if ($image = $page->thumbnail()->toFile()): ?>
      <div>
        <figure class="relative md:shadow-frame unselectable">
          <svg id="drauu-canvas" class="cursor-crosshair touch-pinch-zoom absolute t-0 l-0 w-full h-full z-10 hidden md:block"></svg>
          <img
            src="<?= $image->url() ?>"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            alt="<?= $image->alt() ?>"
          >
        </figure>

        <div class="drauu-controls flex-wrap items-center gap-1 mt-xs hidden md:flex md:ml-3xl">
          <button id="m-stylus" class="is-active" title="<?= t('drauu.stylus') ?>">✍️</button>
          <button id="m-draw" title="<?= t('drauu.draw') ?>">✏️</button>
          <!-- <button id="m-eraser" title="<?= t('drauu.eraser') ?>">⚪️</button> -->
          <button id="m-line" title="<?= t('drauu.line') ?>">⁄</button>
          <select id="size" title="<?= t('drauu.size') ?>">
            <?php foreach (range(1, 9) as $number): ?>
              <option value="<?= $number ?>"<?php e($number === 3, ' selected') ?>><?= $number ?></option>
            <?php endforeach ?>
          </select>
          <button id="clear" class="ml-auto" title="<?= t('drauu.clear') ?>">🗑</button>
          <button id="download" title="Download">📥</button>
        </div>
      </div>
    <?php endif ?>

    <div class="prose px-lg sm:px-0">
      <?php foreach ($page->bio()->toBlocks() as $block): ?>
        <?php /** @var \Kirby\Cms\Block $block */ ?>
        <?php if ($block->type() === 'heading'): ?>
          <h1 class="title text-size-4xl font-900"><?= $block->text() ?></h1>
        <?php else: ?>
          <?= $block ?>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>
</div>

<div class="pb-8xl">
  <div class="content max-w-screen-lg">
    <div class="pt-5xl border-t">
      <?php foreach ($page->cv()->toLayouts() as $layout): ?>
        <div class="grid gap-x-3xl grid-cols-[repeat(auto-fit,minmax(calc(22ch-1.875rem),1fr))]">
          <?php foreach ($layout->columns() as $column): ?>
            <div class="prose children-[p]:indent-0">
              <?php foreach ($column->blocks() as $block): ?>
                <?php /** @var \Kirby\Cms\Block $block */ ?>
                <?php if ($block->type() === 'heading'): ?>
                  <h2 class="text-sm uppercase tracking-[0.125ch]"><?= $block->text() ?></h2>
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
