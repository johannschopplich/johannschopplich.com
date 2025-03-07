<?php

/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $height */

$heightMap = [
  'tight' => [
    'base' => 'clamp(25svh, 50vw, 25svh)',
    'md' => 'clamp(25svh, 50vw, 37.5svh)'
  ],
  'normal' => [
    'base' => 'clamp(37.5svh, 50vw, 50svh)',
    'md' => 'clamp(25svh, 50vw, 50svh)'
  ],
  'loose' => [
    'base' => 'clamp(50svh, 50vw, 75svh)',
    'md' => 'clamp(25svh, 50vw, 75svh)'
  ]
];

$selectedHeight = $height ?? 'loose';

$cssVars = implode(';', array_map(
  fn($breakpoint) => "--cell-{$breakpoint}: {$heightMap[$selectedHeight][$breakpoint]}",
  array_keys($heightMap[$selectedHeight])
));

?>
<div
  class="w-full flex gap-xs snap-x snap-mandatory overflow-x-auto py-px"
  style="<?= $cssVars ?>"
  data-slider>
  <?php foreach ($query as $image): ?>
    <?php
    /** @var \Kirby\Cms\File $image */
    $settings = $image->gallery()->toObject();
    $mockup = $settings->mockup()->or('none')->value();
    $isDocument = $mockup === 'document';
    $isMobile = $mockup === 'mobile';
    $isDesktop = $mockup === 'desktop';
    ?>
    <div class="group shrink-0 snap-center snap-always">
      <div
        class="<?= trim(implode(' ', [
                  'overflow-hidden',
                  $mockup !== 'none' ? 'relative bg-$bg h-$cell-base md:h-$cell-md' : '',
                  ($isDocument || $isMobile) ? 'px-[4.5rem] py-xl md:px-8xl md:py-5xl xl:px-[9rem]' : '',
                  $isDesktop ? 'flex flex-col p-3xl md:p-5xl' : ''
                ]), ' ') ?>"
        style="--bg: <?= $settings->bgColor()->or('var(--un-color-contrast-lower)') ?>">
        <?php if ($isMobile): ?>
          <div class="hidden absolute left-1/2 h-[1px] w-[14%] bg-stone-900 ml-[-7%] rounded-full translate-y-[-4px] md:block md:bottom-5xl md:h-[2px] md:translate-y-[-6px]"></div>
        <?php elseif ($isDesktop): ?>
          <div class="flex h-4 items-center gap-1 border-x border-x-solid border-t border-t-solid border-stone-900 rounded-t-lg px-1.5">
            <?php foreach (range(1, 3) as $i): ?>
              <div class="h-1.5 w-1.5 border border-solid border-stone-900 rounded-full"></div>
            <?php endforeach ?>
          </div>
        <?php endif ?>

        <img
          loading="lazy"
          class="<?= trim(implode(' ', [
                    'pointer-events-none object-cover w-auto',
                    $mockup === 'none' ? 'max-w-[calc(100vw-2.25rem)] h-$cell-base md:h-$cell-md' : 'border border-solid border-stone-900',
                    $isMobile ? 'h-full rounded-xl md:rounded-2xl' : '',
                    $isDocument ? 'h-full' : '',
                    $isDesktop ? 'h-[calc(100%-1rem)] rounded-b-lg' : ''
                  ]), ' ') ?>"
          src="<?= $image->thumbhashUri() ?>"
          data-srcset="<?= $image->srcset() ?>"
          data-sizes="auto"
          width="<?= $image->width() ?>"
          height="<?= $image->height() ?>"
          style="<?= $mockup === 'none' ? 'aspect-ratio:' . $image->width() . '/' . $image->height() : '' ?>"
          alt="<?= $image->alt()->or('')->escape() ?>">
      </div>
    </div>
  <?php endforeach ?>

  <?= $slot ?>
</div>