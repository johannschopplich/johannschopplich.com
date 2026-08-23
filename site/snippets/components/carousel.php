<?php

use OzdemirBurak\Iris\Color\Hex;

/** @var \Kirby\Cms\Files $query */
/** @var string|null $height */

// A mockup cell takes this as a fixed height, an image without one is only
// capped by it. Cells stretch to the tallest, so a short image gets matted.
// The name is a contract: `home.php` sizes its slotted card with `h-$cell-h`.
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
      $borderHex = $settings->borderColor()->value() ?: null;

      // A mockup-less image is sized by width, so below `md` it can end shorter
      // than the row and the cell shows through above and below it. A `bgColor`
      // has to suppress the thumbhash – a background image cannot sit under it.
      $backdrop = $mockup === 'none' && $bgHex === null ? $image->thumbhashUri() : null;

      // Only the document has to separate from its own mat; the browser outline
      // is part of the drawing.
      if ($borderHex === null && $isDocument && $bgHex !== null) {
        $bg = new Hex($bgHex);
        $borderHex = (string) ($bg->isDark() ? $bg->lighten(20) : $bg->darken(20));
      }
      $cellBorder = $borderHex ?? ($isDocument ? 'var(--un-color-contrast-low)' : 'var(--tw-color-stone-900)');
      ?>
      <div
        class="shrink-0 max-w-[100vw]"
        role="group"
        aria-roledescription="<?= t('carousel.slide') ?>"
        aria-label="<?= $index + 1 . ' / ' . $query->count() ?>"
      >
        <div
          class="overflow-hidden bg-$cell-bg bg-cover bg-center <?= match ($mockup) {
            'document', 'mobile' => 'px-5xl py-xl h-$cell-h md:px-8xl md:py-5xl xl:px-[9rem]',
            'desktop' => '[--max-ar:1.6] flex flex-col items-center justify-center p-lg h-$cell-h md:p-5xl',
            default => 'flex items-center justify-center h-full'
          } ?>"
          style="--cell-bg: <?= $bgHex ?? 'var(--un-color-contrast-lower)' ?>; --cell-border: <?= $cellBorder ?><?= $backdrop ? '; background-image: url(' . $backdrop . ')' : '' ?>"
        >
          <?php if ($mockup === 'desktop'): ?>
            <div class="self-stretch flex items-center gap-1 px-1.5 h-4 border-x border-x-solid border-t border-t-solid border-$cell-border rounded-t-lg">
              <?php foreach (range(1, 3) as $i): ?>
                <div class="h-1.5 w-1.5 border border-solid border-$cell-border rounded-full"></div>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <?php if ($isDocument): ?>
            <div class="p-2 h-full w-fit border border-dashed border-$cell-border md:p-3">
          <?php endif ?>

          <img
            class="pointer-events-none select-none aspect-[var(--ar)] <?= match ($mockup) {
              'document' => 'object-cover w-auto h-full shadow-[0_1px_3px_0_oklch(0_0_0/0.1),_0_4px_12px_-2px_oklch(0_0_0/0.08)]',
              'mobile' => 'object-cover w-auto h-full rounded-2xl shadow-[0_0_0_1px_oklch(1_0_0/0.1),_0_0_0_1px_oklch(0_0_0/0.1),_0_8px_24px_-4px_oklch(0_0_0/0.12),_0_2px_6px_-1px_oklch(0_0_0/0.1)]',
              // Fills the cell minus the `1rem` chrome bar, under two caps:
              // `--max-ar` is how wide the mockup may grow relative to its own
              // height – uncapped, a flat screenshot turns into a slab – and
              // below `md` the cell runs full width, so its `p-lg` counts.
              'desktop' => 'w-auto h-[min(calc((100%-1rem)*min(1,var(--max-ar)/var(--ar))),calc((100vw-2.25rem)/var(--ar)))] border border-solid border-$cell-border rounded-b-lg',
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
