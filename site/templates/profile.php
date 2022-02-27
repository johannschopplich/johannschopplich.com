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
          <svg class="drauu-canvas absolute t-0 l-0 w-full h-full z-10 hidden md:block"></svg>
          <img
            src="<?= $image->url() ?>"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            alt="<?= $image->alt() ?>"
          >
        </figure>

        <div class="drauu-app flex-wrap items-center gap-1 mt-xs hidden md:flex md:ml-3xl">
          <button id="m-stylus" class="is-active" title="<?= t('drauu.stylus') ?>">✍️</button>
          <button id="m-draw" title="<?= t('drauu.draw') ?>">✏️</button>
          <button id="m-line" title="<?= t('drauu.line') ?>">⁄</button>
          <select id="size" title="<?= t('drauu.size') ?>">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" selected>3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
          </select>
          <button id="clear" class="ml-auto" title="<?= t('drauu.clear') ?>">🗑</button>
          <button id="download" title="Download">📥</button>
        </div>
      </div>
    <?php endif ?>

    <div class="prose prose-bio px-lg sm:px-0">
      <?= $page->bio()->toBlocks() ?>
    </div>
  </div>
</div>

<div class="pb-8xl">
  <div class="content max-w-screen-lg">
    <div class="border-t grid gap-x-3xl grid-cols-[repeat(auto-fit,minmax(calc(22ch-2rem),1fr))]">
      <?php foreach ($page->cv()->toStructure() as $section): ?>
        <div class="prose prose-cv">
          <?= $section->text()->kt() ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</div>
