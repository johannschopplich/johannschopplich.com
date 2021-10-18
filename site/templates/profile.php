<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<div class="section-bio pt-xl">
  <div class="container is-lg">
    <div class="bio-content content">
      <?= $page->profileText()->kt() ?>
    </div>

    <?php if ($image = $page->thumbnail()->toFile()): ?>
      <div>
        <figure class="position-relative bio-picture stretched-fullwidth@md">
          <svg class="drauu-canvas unselectable d-none md:d-block"></svg>
          <img
            src="<?= $image->url() ?>"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            alt="<?= $image->alt() ?>"
          >
        </figure>

        <div class="drauu-app mt-s d-none md:d-flex">
          <button id="m-stylus" class="is-active" title="Stylus">✍️</button>
          <button id="m-draw" title="Draw">✏️</button>
          <button id="m-line" title="Line">⁄</button>
          <input id="size" type="number" min="1" max="9" value="3" step="1" title="Size">
          <button id="clear" class="ml-auto" title="Clear">🗑</button>
          <button id="download" title="Download">📥</button>
        </div>
      </div>
    <?php endif ?>
  </div>
</div>

<div class="section-cv pb-xl">
  <div class="container is-md">
    <?php foreach ($page->bio()->toStructure() as $section): ?>
      <div class="cv-content content">
        <?= $section->text()->kt() ?>
      </div>
    <?php endforeach ?>
  </div>
</div>

<?php snippet('footer') ?>
