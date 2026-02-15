<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-5xl md:pt-8xl md:pb-8xl">
  <div class="max-w-[72rem] flex flex-col gap-5xl md:flex-row md:items-stretch md:px-[max(4vw,var(--spacing-3xl))]">
    <?php if ($image = $page->thumbnail()->toFile()): ?>
      <div class="grow min-w-0 md:min-w-min">
        <figure class="relative select-none shadow-[var(--un-frame-shadow-template)_var(--un-color-border)]">
          <svg id="drauu-canvas" class="absolute top-0 left-0 z-10 hidden h-full w-full cursor-crosshair touch-pinch-zoom md:block"></svg>
          <img
            srcset="<?= $image->srcset() ?>"
            sizes="(min-width: 768px) 320px, 100vw"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            alt="<?= $image->alt() ?>"
          >
        </figure>

        <div
          x-data="drauuControls"
          class="drauu-controls mt-xs ml-[4px] hidden items-center gap-1 md:flex">
          <button
            @click="setMode('stylus')"
            :class="{ 'is-active': mode === 'stylus' }"
            title="<?= t('drauu.stylus') ?>">✍️</button>
          <button
            @click="setMode('draw')"
            :class="{ 'is-active': mode === 'draw' }"
            title="<?= t('drauu.draw') ?>">✏️</button>
          <button
            @click="setMode('line')"
            :class="{ 'is-active': mode === 'line' }"
            title="<?= t('drauu.line') ?>">⁄</button>
          <select
            x-model.number="brushSize"
            @change="updateBrushSize()"
            title="<?= t('drauu.size') ?>">
            <?php foreach (range(1, 9) as $number): ?>
              <option value="<?= $number ?>" <?php e($number === 3, ' selected') ?>><?= $number ?></option>
            <?php endforeach ?>
          </select>
          <button @click="clear()" class="ml-auto" title="<?= t('drauu.clear') ?>">🗑</button>
          <button @click="download()" title="Download">📥</button>
        </div>
      </div>
    <?php endif ?>

    <div class="prose max-w-prose px-lg md:px-0 xl:flex xl:flex-col xl:pb-[2.25rem]">
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

<div class="pb-8xl border-t border-t-solid border-contrast-low dark:border-contrast-lower">
  <div class="content pt-5xl md:pt-8xl">
    <div class="max-w-[72rem]">
      <section>
        <h2 class="title text-2xl mb-3xl">
          <?= t('cv.career') ?>
        </h2>

        <dl class="grid grid-cols-[auto_1fr_auto] gap-x-lg gap-y-5xl">
          <?php foreach ($page->career()->toStructure() as $entry): ?>
            <dt class="overline md:mt-0.5">
              <?= $entry->period()->escape() ?>
            </dt>

            <hr class="border-dotted border-contrast-medium mt-3" aria-hidden="true">

            <dd class="max-w-prose">
              <div class="group relative">
                <p class="title text-lg">
                  <?php if ($entry->url()->isNotEmpty()): ?>
                    <a href="<?= $entry->url() ?>" class="link-default" target="_blank">
                      <span class="absolute inset-0" aria-hidden="true"></span>
                      <?= $entry->company()->escape() ?>
                    </a>
                  <?php else: ?>
                    <?= $entry->company()->escape() ?>
                  <?php endif ?>
                </p>
                <p class="subtext text-primary-accent">
                  <?= $entry->role()->escape() ?>
                </p>
              </div>

              <?php if ($entry->description()->toBlocks()->isNotEmpty()): ?>
                <div class="prose text-sm mt-sm dark:text-contrast-medium">
                  <?php foreach ($entry->description()->toBlocks() as $block): ?>
                    <?= $block ?>
                  <?php endforeach ?>
                </div>
              <?php endif ?>
            </dd>
          <?php endforeach ?>
        </dl>
      </section>
    </div>
  </div>
  <?php endsnippet() ?>
