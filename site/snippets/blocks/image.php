<?php

use Kirby\Http\Url;
use Kirby\Toolkit\Html;

/** @var \Kirby\Cms\Block $block */
$alt     = $block->alt();
$caption = $block->caption();
$link    = $block->link();
$props   = $block->properties();
$img     = null;
$urlPath = Url::path(Url::current());
$isFeed  = preg_match('/^feeds?\/(?:rss|json)/', $urlPath);

if ($block->location() === 'web') {
  $img = Html::img($block->src(), ['alt' => $alt]);
} elseif ($image = $block->image()->toFile()) {
  if ($alt->isEmpty()) $alt = $image->alt();
  if ($caption->isEmpty()) $caption = $image->caption();

  $img = Html::img(
    // Disable blurry images images for feeds
    $isFeed ? $image->resize(1024)->url() : $image->placeholderUri(),
    [
      'alt' => $alt,
      'data-srcset' => $image->srcset(),
      'data-sizes' => 'auto',
      'data-lazyload' => 'true',
      'width' => $image->width(),
      'height' => $image->height()
    ]
  );
}

if ($img === null) return;

?>
<figure<?= attr(['class' => $props], ' ') ?>>
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
