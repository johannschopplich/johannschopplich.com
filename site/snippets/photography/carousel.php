<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div class="carousel splide">
  <div class="splide__track">
    <ul class="splide__list">
      <?php foreach ($query as $image): ?>
        <?php /** @var \Kirby\Cms\File $image */ ?>
        <li class="splide__slide">
          <img
            class="max-h-full object-contain"
            src="<?= $image->placeholderUri() ?>"
            data-splide-lazy="<?= $image->url() ?>"
            alt="<?= $image->alt()->escape() ?>"
          >
        </li>
      <?php endforeach ?>

      <li class="splide__slide w-[min(65vw,25rem)] flex items-center justify-center border">
        <a href="<?= $query->first()->parent()->url() ?>" class="action-button">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <?= t('photography.morePhotos') ?><span class="i-mdi-arrow-right ml-1"></span>
        </a>
      </li>
    </ul>
  </div>
</div>
