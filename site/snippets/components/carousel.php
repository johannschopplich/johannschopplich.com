<?php

/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $height */

$selectedHeight = match ($height ?? null) {
  'tight', 'loose' => $height,
  default => 'loose',
};
$totalSlides = $query->count();

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
      /** @var \Kirby\Cms\File $image */
      $settings = $image->gallery()->toObject();
      $mockup = $settings->mockup()->or('none')->value();
      $isDocument = $mockup === 'document';
      $isMobile = $mockup === 'mobile';
      $isDesktop = $mockup === 'desktop';
      ?>
      <div
        class="shrink-0 min-w-0 max-w-[100vw]"
        role="group"
        aria-roledescription="<?= t('carousel.slide') ?>"
        aria-label="<?= $index + 1 ?> / <?= $totalSlides ?>"
      >
        <div
          class="<?= trim(implode(' ', [
                    'overflow-hidden',
                    $mockup !== 'none' ? 'relative bg-$bg' : '',
                    ($isDocument || $isMobile) ? 'h-$cell-h px-[4.5rem] py-xl md:px-8xl md:py-5xl xl:px-[9rem]' : '',
                    $isDesktop ? 'flex flex-col items-center justify-center h-$cell-h p-3xl md:p-5xl w-[calc(100vw-2.25rem)] md:w-auto' : ''
                  ]), ' ') ?>"
          style="--bg: <?= $settings->bgColor()->or('var(--un-color-contrast-lower)') ?>"
        >
          <?php if ($isMobile): ?>
            <div class="absolute left-1/2 bottom-$spacing-xl w-[20%] h-[1.5px] bg-stone-900 rounded-full translate-x-[-50%] translate-y-[-4px] md:bottom-$spacing-5xl md:w-[12%] md:h-[2px] md:translate-y-[-6px]"></div>
          <?php elseif ($isDesktop): ?>
            <div class="self-stretch flex h-4 items-center gap-1 border-x border-x-solid border-t border-t-solid border-stone-900 rounded-t-lg px-1.5">
              <?php foreach (range(1, 3) as $i): ?>
                <div class="h-1.5 w-1.5 border border-solid border-stone-900 rounded-full"></div>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <?php
          $imgStyles = $mockup === 'none'
            ? implode('; ', [
              '--img-h: min(calc(100vw * ' . $image->height() . ' / ' . $image->width() . '), var(--cell-h))',
              'aspect-ratio: ' . $image->width() . '/' . $image->height()
            ])
            : '';
          ?>
          <img
            loading="lazy"
            class="<?= trim(implode(' ', [
                      'pointer-events-none select-none',
                      $mockup === 'none' ? 'w-auto max-w-[100vw] h-$img-h' : '',
                      ($isMobile || $isDocument) ? 'w-auto h-full object-cover border border-solid border-stone-900' : '',
                      $isMobile ? 'rounded-xl md:rounded-2xl' : '',
                      $isDesktop ? 'w-full h-auto md:w-auto md:h-[calc(100%-1rem)] border border-solid border-stone-900 rounded-b-lg' : ''
                    ]), ' ') ?>"
            src="<?= $image->thumbhashUri() ?>"
            data-srcset="<?= $image->srcset() ?>"
            data-sizes="auto"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            style="<?= $imgStyles ?>"
            alt="<?= $image->alt()->or('')->escape() ?>"
          >
        </div>
      </div>
    <?php endforeach ?>

    <?= $slot ?>
  </div>
</div>
