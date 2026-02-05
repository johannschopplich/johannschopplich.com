<?php

/** @var \Kirby\Cms\Block $block */

if (!($image = $block->image()->toFile())) {
  return;
}

$caption = $block->caption();
$size = $block->size()->or(50)->toInt();

if ($caption->isEmpty()) {
  $caption = $image->caption()->permalinksToUrls();
}

?>
<figure class="is-outset">
  <div class="h-64" style="background-image: url(<?= $image->resize(1024)->url() ?>); background-size: <?= $size ?>%;"></div>

  <?php if ($caption->isNotEmpty()): ?>
    <figcaption>
      <?= $caption ?>
    </figcaption>
  <?php endif ?>
</figure>
