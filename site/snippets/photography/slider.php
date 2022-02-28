<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div
  class="slider w-full flex gap-xs snap-x snap-mandatory overflow-x-auto"
  style="--cell: clamp(35vh, 50vw, 75vh); --cursor: grab;"
>
  <?php foreach ($query as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <div class="cursor-$cursor shrink-0 snap-always snap-center">
      <?php snippet('helpers/img', [
        'image' => $image,
        'class' => 'pointer-events-none h-$cell w-auto object-contain'
      ]) ?>
    </div>
  <?php endforeach ?>

  <div class="slider-ignore shrink-0 snap-always snap-end pr-xs">
    <div class="relative h-$cell w-[min(65vw,25rem)] flex items-center justify-center border">
      <a href="<?= $query->first()->parent()->url() ?>" class="action-button">
        <span class="absolute inset-0" aria-hidden="true"></span>
        <?= t('photography.morePhotos') ?><span class="i-mdi-arrow-right ml-1" aria-hidden="true"></span>
      </a>
    </div>
  </div>
</div>
