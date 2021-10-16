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
      <figure class="position-relative bio-picture stretched-fullwidth@md">
        <svg class="drauu"></svg>
        <img
          src="<?= $image->url() ?>"
          width="<?= $image->width() ?>"
          height="<?= $image->height() ?>"
          alt="<?= $image->alt() ?>"
        >
      </figure>
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
