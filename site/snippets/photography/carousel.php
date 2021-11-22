<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div class="carousel carousel-photography splide">
  <div class="splide__track">
    <ul class="splide__list">
      <?php foreach ($query as $image): ?>
        <?php /** @var \Kirby\Cms\File $image */ ?>
        <li class="splide__slide">
          <img
            class="carousel-image"
            src="<?= $image->placeholderUri() ?>"
            data-splide-lazy="<?= $image->url() ?>"
            alt="<?= $image->alt()->escape() ?>"
          >
        </li>
      <?php endforeach ?>

      <li class="splide__slide carousel-link flex items-center justify-center">
        <a href="<?= $query->first()->parent()->url() ?>" class="stretched-link due-button-text">
          <?= substr(($t = t('photography.morePhotos')), 0, strrpos($t, ' ')) ?>
        </a>
      </li>
    </ul>
  </div>
</div>
