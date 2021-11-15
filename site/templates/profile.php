<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<div class="section-bio pt-13">
  <div class="container-lg grid items-center gap-13 sm:grid-cols-[8fr,4fr] sm:pb-13">
    <div class="bio-content content">
      <?= $page->profileText()->kt() ?>
    </div>

    <?php if ($image = $page->thumbnail()->toFile()): ?>
      <div>
        <figure class="relative md:framed unselectable -md-full-width">
          <svg class="drauu-canvas absolute inset-0 hidden md:block"></svg>
          <img
            src="<?= $image->url() ?>"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            alt="<?= $image->alt() ?>"
          >
        </figure>

        <div class="drauu-app mt-3 hidden md:flex">
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
  </div>
</div>

<div class="pb-13">
  <div class="cv-container container-md">
    <?php foreach ($page->bio()->toStructure() as $section): ?>
      <div class="cv-content content">
        <?= $section->text()->kt() ?>
      </div>
    <?php endforeach ?>
  </div>
</div>

<?php snippet('footer') ?>
