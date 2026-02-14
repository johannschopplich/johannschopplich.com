<?php

/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $width */

?>
<masonry-grid <?= attr([
  'class' => 'grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-column-max-width,25rem),100%),1fr))] gap-5xl justify-center children:self-start sm:gap-lg',
  'style' => $width ? '--masonry-column-max-width: ' . $width : null,
]) ?>>
  <?php foreach ($query as $image): ?>
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
        <figcaption class="content prose text-size-xs leading-dense font-500 py-2 text-center">
          <?= $image->caption() ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endforeach ?>
</masonry-grid>
