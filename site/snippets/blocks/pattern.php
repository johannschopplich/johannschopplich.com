<?php

/** @var \Kirby\Cms\Block $block */
$caption = $block->caption();
$src     = null;
$size    = $block->size()->or(50)->toInt();

if ($image = $block->image()->toFile()) {
  if ($caption->isEmpty()) {
    $caption = $image->caption()->permalinksToUrls();
  }

  $src = $image->resize(1024)->url();
} else {
  return;
}

?>
<figure class="is-outset">
  <div
    class="h-64"
    style="background-image: url(<?= $src ?>); background-size: <?= $size ?>%;"></div>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption>
      <?= $caption ?>
    </figcaption>
  <?php endif ?>
</figure>
