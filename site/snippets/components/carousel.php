<?php

use OzdemirBurak\Iris\Color\Hex;

/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $height */

$selectedHeight = match ($height ?? null) {
  'tight', 'loose' => $height,
  default => 'loose',
};

?>
<div
  class="overflow-hidden"
  tabindex="0"
  role="region"
  aria-roledescription="<?= t('carousel.roledescription') ?>"
  aria-label="<?= $ariaLabel ?? t('carousel.label') ?>"
  data-carousel
  data-height="<?= $selectedHeight ?>"
>
  <div class="flex gap-xs cursor-grab active:cursor-grabbing" aria-live="polite">
    <?php foreach ($query->values() as $index => $image): ?>
      <?php
      /** @var \Kirby\Content\Content */
      $settings = $image->gallery()->toObject();
      $mockup = $settings->mockup()->or('none')->value();
      $isDocument = $mockup === 'document';
      $isMobile = $mockup === 'mobile';
      $isDesktop = $mockup === 'desktop';
      $hasBgColor = $settings->bgColor()->isNotEmpty();
      $bgHex = $hasBgColor ? $settings->bgColor()->value() : null;
      ?>
      <div
        class="shrink-0 min-w-0 max-w-[100vw]"
        role="group"
        aria-roledescription="<?= t('carousel.slide') ?>"
        aria-label="<?= $index + 1 . ' / ' . $query->count() ?>"
      >
        <div
          class="<?= trim(implode(' ', [
            'overflow-hidden',
            $mockup !== 'none' ? 'relative bg-$cell-bg' : '',
            ($isDocument || $isMobile) ? 'h-$cell-h px-[4.5rem] py-xl md:px-8xl md:py-5xl xl:px-[9rem]' : '',
            $isDesktop ? 'flex flex-col items-center justify-center h-$cell-h p-3xl md:p-5xl w-[calc(100vw-2.25rem)] md:w-auto' : ''
          ]), ' ') ?>"
          style="--cell-bg: <?= $bgHex ?? 'var(--un-color-contrast-lower)' ?>"
        >
          <?php if ($isDesktop): ?>
            <div class="self-stretch flex h-4 items-center gap-1 border-x border-x-solid border-t border-t-solid border-stone-900 rounded-t-lg px-1.5">
              <?php foreach (range(1, 3) as $i): ?>
                <div class="h-1.5 w-1.5 border border-solid border-stone-900 rounded-full"></div>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <?php if ($isDocument):
            $bgColor = $hasBgColor ? new Hex($bgHex) : null;
            $borderColor = $bgColor ? ($bgColor->isDark() ? $bgColor->lighten(20) : $bgColor->darken(20)) : null;
          ?>
            <div
              class="h-full w-fit p-2 border border-dashed border-$cell-border md:p-3"
              style="--cell-border: <?= $borderColor ?? 'var(--un-color-contrast-low)' ?>"
            >
          <?php endif ?>

          <img
            class="<?= trim(implode(' ', [
              'pointer-events-none select-none',
              $mockup === 'none' ? 'w-auto max-w-[100vw] h-$img-h' : '',
              $isDocument ? 'w-auto h-full object-cover shadow-[0_1px_3px_0_oklch(0_0_0/0.1),_0_4px_12px_-2px_oklch(0_0_0/0.08)]' : '',
              $isMobile ? 'w-auto h-full object-cover rounded-2xl shadow-[0_0_0_1px_oklch(1_0_0/0.1),_0_0_0_1px_oklch(0_0_0/0.1),_0_8px_24px_-4px_oklch(0_0_0/0.12),_0_2px_6px_-1px_oklch(0_0_0/0.1)]' : '',
              $isDesktop ? 'w-full h-auto md:w-auto md:h-[calc(100%-1rem)] border border-solid border-stone-900 rounded-b-lg' : ''
            ]), ' ') ?>"
            src="<?= $image->thumbhashUri() ?>"
            data-srcset="<?= $image->srcset() ?>"
            data-sizes="auto"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            style="<?= trim(implode('; ', [
              $mockup === 'none' ? '--img-h: min(calc(100vw * ' . $image->height() . ' / ' . $image->width() . '), var(--cell-h))' : '',
              'aspect-ratio: ' . $image->width() . '/' . $image->height()
            ]), ' ') ?>"
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
</div>
