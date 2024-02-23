<?php

use Kirby\Http\Url;
use Kirby\Toolkit\Html;

/** @var \Kirby\Cms\Block $block */
$alt     = $block->alt();
$caption = $block->caption();
$link    = $block->link();
$props   = $block->properties();
$isFeed  = preg_match('/feeds\/(?:rss|json)$/', Url::current());
$img     = null;

if ($block->location() === 'web') {
    $img = Html::img($block->src(), ['alt' => $alt]);
} elseif ($image = $block->image()->toFile()) {
    if ($alt->isEmpty()) {
        $alt = $image->alt();
    }
    if ($caption->isEmpty()) {
        $caption = $image->caption()->permalinksToUrls();
    }

    $img = Html::img(
        // Disable blurry images images for feeds
        $isFeed ? $image->resize(1024)->url() : $image->thumbhashUri(),
        [
            'loading' => 'lazy',
            'data-srcset' => $image->srcset(),
            'data-sizes' => 'auto',
            'width' => $image->width(),
            'height' => $image->height(),
            'style' => 'aspect-ratio: ' . $image->width() . '/' . $image->height(),
            'alt' => $alt->isNotEmpty() ? $alt->escape() : null
        ]
    );
} else {
    return;
}

?>
<figure <?= attr([
  'class' => $props ?? null
]) ?>>
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
