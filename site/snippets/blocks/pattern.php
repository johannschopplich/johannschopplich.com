<?php

use Kirby\Http\Url;

/** @var \Kirby\Cms\Block $block */

if (!($image = $block->image()->toFile())) {
  return;
}

$caption = $block->caption();
$size = $block->size()->or(50)->toInt();

if ($caption->isEmpty()) {
  $caption = $image->caption()->permalinksToUrls();
}

// Feed readers strip background images – show the pattern as a plain image there
$isFeed = preg_match('/feeds\/(?:rss|json)$/', Url::current());

?>
<figure class="is-outset">
  <?php if ($isFeed): ?>
    <img src="<?= $image->resize(1024)->url() ?>" alt="">
  <?php else: ?>
    <div class="h-64" style="background-image: url(<?= $image->resize(1024)->url() ?>); background-size: <?= $size ?>%;"></div>
  <?php endif ?>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption>
      <?= $caption ?>
    </figcaption>
  <?php endif ?>
</figure>
