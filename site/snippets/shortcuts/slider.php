<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var bool|null $zoomable */
/** @var string|null $height */

$heightMap = [
  'tight' => 'clamp(36vh, 50vw, 25vh)',
  'normal' => 'clamp(36vh, 50vw, 50vh)',
  'loose' => 'clamp(36vh, 50vw, 75vh)'
];
?>
<div
  class="gap-xs flex w-full snap-x snap-mandatory overflow-x-auto"
  style="--cell: <?= $heightMap[$height ?? 'normal'] ?>"
>
  <?php foreach ($query as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <?php $settings = $image->gallery()->toObject() ?>
    <?php $tag = $settings->link()->isNotEmpty() ? 'a' : 'div' ?>
    <<?= $tag ?> class="shrink-0 snap-center snap-always first:snap-start"<?= attr([
      'href' => $settings->link()->isNotEmpty() ? $settings->link()->value() : null,
      'target' => $settings->link()->isNotEmpty() ? '_blank' : null,
      'rel' => $settings->link()->isNotEmpty() ? 'noopener' : null
    ], ' ') ?>>
      <?php if ($settings->hasBorder()->isTrue()): ?>
        <div class="h-$cell bg-$bg flex items-center justify-center px-[5vh]" style="--bg: <?= $settings->bgColor()->or('var(--du-color-contrast-lower)') ?>">
          <?php snippet('shortcuts/img', [
            'file' => $image,
            'class' => 'h-[80%] w-auto rounded-xl border border-theme-base object-contain',
            'zoomable' => $settings->link()->isEmpty() ? true : null
          ]) ?>
        </div>
      <?php else: ?>
        <?php snippet('shortcuts/img', [
          'file' => $image,
          'class' => 'h-$cell w-auto object-contain',
          'zoomable' => $zoomable ?? null
        ]) ?>
      <?php endif ?>
    </<?= $tag ?>>
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
