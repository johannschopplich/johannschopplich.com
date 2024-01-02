<?php
/** @var \Kirby\Cms\Block $block */
?>
<masonry-grid>
  <?php foreach ($block->images()->toFiles() as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <?= \Kirby\Cms\Block::factory([
      'type' => 'image',
      'content' => [
        'image' => 'file://' . $image->uuid()->id()
      ]
    ])->toHtml() ?>
  <?php endforeach ?>
</masonry-grid>
