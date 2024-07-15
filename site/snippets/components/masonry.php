<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $width */

// Note: The `masonry-grid` class is only applied  as fallback until the web component is initialized
?>
<masonry-grid <?= attr([
  'class' => 'masonry-grid gap-5xl sm:gap-lg',
  'style' => $width ? '--masonry-column-max-width: ' . $width : null,
]) ?>>
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
        style="aspect-ratio: <?= $image->width() ?>/<?= $image->height() ?>"
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
