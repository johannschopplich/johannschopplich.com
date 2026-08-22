<?php

use OzdemirBurak\Iris\Color\Hex;

/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $height */

// Height the row aims for: a mockup cell takes it as a fixed height, an image
// without one is sized by width and only capped by it. Cells stretch to the
// tallest, so a short image gets matted. `home.php` reads it as `h-$cell-h`.
$cellHeight = match ($height ?? null) {
  'tight' => '[--cell-h:25svh] md:[--cell-h:min(50vw,37.5svh)] 2xl:[--cell-h:25svh]',
  default => '[--cell-h:clamp(50svh,50vw,75svh)] md:[--cell-h:min(50vw,75svh)] 2xl:[--cell-h:min(50vw,50svh)]'
};

?>
<div
  class="overflow-hidden <?= $cellHeight ?>"
  tabindex="0"
  role="region"
  aria-roledescription="<?= t('carousel.roledescription') ?>"
  aria-label="<?= $ariaLabel ?? t('carousel.label') ?>"
  data-carousel
>
  <div class="flex gap-xs cursor-grab active:cursor-grabbing" aria-live="polite">
    <?php foreach ($query->values() as $index => $image): ?>
      <?php
      /** @var \Kirby\Content\Content $settings */
      $settings = $image->gallery()->toObject();
      $mockup = $settings->mockup()->or('none')->value();
      $isDocument = $mockup === 'document';
      $bgHex = $settings->bgColor()->value() ?: null;
      ?>
      <div
        class="shrink-0 max-w-[100vw]"
        role="group"
        aria-roledescription="<?= t('carousel.slide') ?>"
        aria-label="<?= $index + 1 . ' / ' . $query->count() ?>"
      >
        <div
          class="overflow-hidden bg-$cell-bg <?= match ($mockup) {
            'document', 'mobile' => 'px-[4.5rem] py-xl h-$cell-h md:px-8xl md:py-5xl xl:px-[9rem]',
            'desktop' => 'flex flex-col items-center justify-center p-lg h-$cell-h md:p-5xl',
            default => 'flex items-center justify-center h-full'
          } ?>"
          style="--cell-bg: <?= $bgHex ?? 'var(--un-color-contrast-lower)' ?>"
        >
          <?php if ($mockup === 'desktop'): ?>
            <div class="self-stretch flex items-center gap-1 px-1.5 h-4 border-x border-x-solid border-t border-t-solid border-stone-900 rounded-t-lg">
              <?php foreach (range(1, 3) as $i): ?>
                <div class="h-1.5 w-1.5 border border-solid border-stone-900 rounded-full"></div>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <?php if ($isDocument):
            $bgColor = $bgHex ? new Hex($bgHex) : null;
            $borderColor = $bgColor ? ($bgColor->isDark() ? $bgColor->lighten(20) : $bgColor->darken(20)) : null;
          ?>
            <div
              class="p-2 h-full w-fit border border-dashed border-$cell-border md:p-3"
              style="--cell-border: <?= $borderColor ?? 'var(--un-color-contrast-low)' ?>"
            >
          <?php endif ?>

          <img
            class="pointer-events-none select-none aspect-[var(--ar)] <?= match ($mockup) {
              'document' => 'object-cover w-auto h-full shadow-[0_1px_3px_0_oklch(0_0_0/0.1),_0_4px_12px_-2px_oklch(0_0_0/0.08)]',
              'mobile' => 'object-cover w-auto h-full rounded-2xl shadow-[0_0_0_1px_oklch(1_0_0/0.1),_0_0_0_1px_oklch(0_0_0/0.1),_0_8px_24px_-4px_oklch(0_0_0/0.12),_0_2px_6px_-1px_oklch(0_0_0/0.1)]',
              // Sized by height so it fills the row, under three bounds: the cell
              // minus the `1rem` chrome bar, the viewport minus the `2.25rem` of
              // padding the cell carries below `md` where it runs full width, and
              // `1.4` row heights – uncapped, a flat screenshot scales into a slab
              // several times wider than the row is tall.
              'desktop' => 'w-auto h-[min(calc(100%-1rem),calc((100vw-2.25rem)/var(--ar)),calc(var(--cell-h)*1.4/var(--ar)))] border border-solid border-stone-900 rounded-b-lg',
              default => 'w-auto h-[min(calc(100vw/var(--ar)),var(--cell-h))]'
            } ?>"
            src="<?= $image->thumbhashUri() ?>"
            data-srcset="<?= $image->srcset() ?>"
            data-sizes="auto"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            style="--ar: <?= round($image->width() / $image->height(), 6) ?>"
            alt="<?= $image->alt()->or('')->escape() ?>"
          >

          <?php if ($isDocument): ?>
            </div>
          <?php endif ?>
        </div>
      </div>
    <?php endforeach ?>

    <?= $slot ?>
  </div>

  <?php if (($footer = $slots->footer()) || $query->count() > 1): ?>
    <div class="flex items-center px-lg mt-lg md:px-gutter <?php e($footer, 'justify-between', 'md:hidden') ?>">
      <?php if ($query->count() > 1): ?>
        <span class="text-xs font-medium tabular-nums tracking-[0.05em] text-contrast-medium md:hidden" aria-hidden="true">
          <span data-carousel-current>1</span> / <?= $query->count() ?>
        </span>
      <?php endif ?>
      <?= $footer ?>
    </div>
  <?php endif ?>
</div>
