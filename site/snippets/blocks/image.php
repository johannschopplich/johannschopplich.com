<?php

use Kirby\Cms\File;
use Kirby\Http\Url;
use Kirby\Toolkit\Html;

/** @var \JohannSchopplich\Website\ImageBlock $block */

$alt     = $block->alt();
$caption = $block->caption()->permalinksToUrls();
$link    = $block->link();
$props   = $block->properties();
$isFeed  = preg_match('/feeds\/(?:rss|json)$/', Url::current());
$image   = $block->source();

if ($image) {
  // A mirrored web image has no metadata of its own to fall back to.
  if ($image instanceof File) {
    $alt = $alt->or($image->alt());
    $caption = $caption->or($image->caption()->permalinksToUrls());
  }

  $img = Html::img(
    // Disable blurry images for feeds.
    $isFeed ? $image->resize(1024)->url() : $image->thumbhashUri(),
    [
      'loading' => 'lazy',
      'data-srcset' => $image->srcset(),
      'data-sizes' => 'auto',
      'width' => $image->width(),
      'height' => $image->height(),
      'style' => 'aspect-ratio: ' . $image->width() . '/' . $image->height(),
      // `null` would drop the attribute instead of marking the image decorative.
      'alt' => $alt->value() ?? ''
    ]
  );
} elseif ($block->isRemote() && $block->src()->isNotEmpty()) {
  $img = Html::img($block->src()->value() ?? '', ['alt' => $alt->value() ?? '']);
} else {
  return;
}

?>
<figure <?= attr(['class' => $props ?? null]) ?>>
  <?php if ($link->isNotEmpty()): ?>
    <a href="<?= $link->toUrl() ?>">
      <?= $img ?>
    </a>
  <?php else: ?>
    <?= $img ?>
  <?php endif ?>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption>
      <?= $caption ?>
    </figcaption>
  <?php endif ?>
</figure>
