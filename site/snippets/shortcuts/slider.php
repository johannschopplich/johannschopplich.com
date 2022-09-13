<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div
  class="gap-xs flex w-full snap-x snap-mandatory overflow-x-auto"
  style="--cell: clamp(36vh, 50vw, 75vh);"
>
  <?php foreach ($query as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <div class="shrink-0 snap-center snap-always first:snap-start">
      <?php snippet('shortcuts/img', [
        'file' => $image,
        // pointer-events-none
        'class' => 'h-$cell w-auto object-contain',
        'zoomable' => $zoomable ?? null
      ]) ?>
    </div>
  <?php endforeach ?>

  <?php if ($page->isHomePage()): ?>
    <div class="pr-xs shrink-0 snap-end snap-always" data-slider-ignore>
      <div class="h-$cell relative flex w-[min(65vw,25rem)] items-center justify-center border">
        <a href="<?= $query->first()->parent()->url() ?>" class="action-button">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <?= t('photography.morePhotos') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
        </a>
      </div>
    </div>
  <?php endif ?>
</div>
