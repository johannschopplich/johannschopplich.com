<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div class="carousel is-photography">
  <?php foreach ($query as $image):
  ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <div class="carousel-cell">
      <img
        src="<?= $image->url() ?>"
        loading="lazy"
        class="cell-image"
        alt="<?= $image->alt()->escape() ?>"
      >
    </div>
  <?php endforeach ?>

  <div class="carousel-cell carousel-link">
    <a href="<?= page('photography')->url() ?>" class="button is-accent is-outlined px-s">
      <?= t('photography.morePhotos') ?>
    </a>
  </div>
</div>
