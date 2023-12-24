<?php
/** @var \Kirby\Cms\Files $query */
/** @var string|null $width */
// Apply the `masonry-grid` class only as fallback until the web component is initialized
?>
<masonry-grid class="masonry-grid gap-5xl sm:gap-lg" style="--masonry-column-max-width: <?= $width ?? 'clamp(25rem, 25vw, 30rem)' ?>">
  <?php foreach ($query as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <figure>
      <img
        src="<?= $image->thumbhashUri() ?>"
        loading="lazy"
        data-srcset="<?= $image->srcset() ?>"
        data-sizes="auto"
        data-zoomable
        width="<?= $image->width() ?>"
        height="<?= $image->height() ?>"
        style="aspect-ratio: <?= round($image->ratio(), 2) ?>"
        alt="<?= $image->alt()->or('')->escape() ?>"
      >

      <?php if ($image->caption()->isNotEmpty()): ?>
        <figcaption class="content text-size-xs leading-[calc(var(--du-line-height-normal)*0.875)] font-500 py-2 text-center">
          <?= $image->caption() ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endforeach ?>
</masonry-grid>
