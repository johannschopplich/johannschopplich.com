<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div class="carousel is-photography splide">
  <div class="splide__track">
    <ul class="splide__list">
      <?php foreach ($query as $image): ?>
        <?php /** @var \Kirby\Cms\File $image */ ?>
        <li class="splide__slide">
          <img
            class="splide__slide__image"
            src="<?= $image->url() ?>"
            loading="lazy"
            alt="<?= $image->alt()->escape() ?>"
          >
        </li>
      <?php endforeach ?>

      <?php /*
      <li class="splide__slide splide__slide__link" style="width: min(65vw, 25rem);">
        <a href="<?= page('photography')->url() ?>" class="button is-accent is-outlined px-s">
          <?= t('photography.morePhotos') ?>
        </a>
      </li>
      */ ?>
    </ul>
  </div>
</div>
