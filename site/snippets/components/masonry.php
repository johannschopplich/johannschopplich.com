<?php

use Kirby\Http\Url;

/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $width */

// Disable blurry images for feeds
$isFeed = preg_match('/feeds\/(?:rss|json)$/', Url::current());

?>
<masonry-grid <?= attr([
  'class' => 'grid grid-cols-[repeat(auto-fit,minmax(min(var(--masonry-column-max-width,25rem),100%),1fr))] gap-5xl sm:gap-lg justify-center children:self-start',
  'style' => $width ? '--masonry-column-max-width: ' . $width : null,
]) ?>>
  <?php foreach ($query as $image): ?>
    <figure>
      <img
        src="<?= $isFeed ? $image->resize(1024)->url() : $image->thumbhashUri() ?>"
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
        <figcaption class="content prose py-2 text-xs/dense font-500 text-center">
          <?= $image->caption() ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endforeach ?>
</masonry-grid>
