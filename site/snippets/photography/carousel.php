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
            class="carousel-image"
            src="<?= $image->placeholderUri() ?>"
            data-splide-lazy="<?= $image->url() ?>"
            alt="<?= $image->alt()->escape() ?>"
          >
        </li>
      <?php endforeach ?>

      <li class="splide__slide carousel-link centered-content">
        <a href="<?= $image->parent()->url() ?>" class="stretched-link button is-text">
          <?= substr(t('photography.morePhotos'), 0, -7) ?>
        </a>
      </li>
    </ul>
  </div>
</div>
