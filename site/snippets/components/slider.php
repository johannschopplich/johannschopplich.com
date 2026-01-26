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
  class="overflow-hidden"
  style="<?= $cssVars ?>"
  data-carousel>
  <div class="flex gap-xs cursor-grab active:cursor-grabbing">
    <?php foreach ($query as $image): ?>
      <?php
      /** @var \Kirby\Cms\File $image */
      $settings = $image->gallery()->toObject();
      $mockup = $settings->mockup()->or('none')->value();
      $isDocument = $mockup === 'document';
      $isMobile = $mockup === 'mobile';
      $isDesktop = $mockup === 'desktop';
      ?>
      <div class="shrink-0 min-w-0 max-w-[100vw]">
        <div
          class="<?= trim(implode(' ', [
                    'overflow-hidden',
                    $mockup !== 'none' ? 'relative bg-$bg' : '',
                    ($isDocument || $isMobile) ? 'h-$cell-base md:h-$cell-md px-[4.5rem] py-xl md:px-8xl md:py-5xl xl:px-[9rem]' : '',
                    $isDesktop ? 'flex flex-col items-center justify-center h-$cell-base md:h-$cell-md p-3xl md:p-5xl w-[calc(100vw-2.25rem)] md:w-auto' : ''
                  ]), ' ') ?>"
          style="--bg: <?= $settings->bgColor()->or('var(--un-color-contrast-lower)') ?>">
          <?php if ($isMobile): ?>
            <div class="absolute left-1/2 bottom-xl w-[20%] h-[1.5px] bg-stone-900 rounded-full translate-x-[-50%] translate-y-[-4px] md:bottom-5xl md:w-[12%] md:h-[2px] md:translate-y-[-6px]"></div>
          <?php elseif ($isDesktop): ?>
            <div class="self-stretch flex h-4 items-center gap-1 border-x border-x-solid border-t border-t-solid border-stone-900 rounded-t-lg px-1.5">
              <?php foreach (range(1, 3) as $i): ?>
                <div class="h-1.5 w-1.5 border border-solid border-stone-900 rounded-full"></div>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <img
            loading="lazy"
            class="<?= trim(implode(' ', [
                      'pointer-events-none select-none',
                      $mockup === 'none' ? 'w-auto max-w-[100vw] h-auto max-h-$cell-base md:max-h-$cell-md' : '',
                      ($isMobile || $isDocument) ? 'w-auto h-full object-cover border border-solid border-stone-900' : '',
                      $isMobile ? 'rounded-xl md:rounded-2xl' : '',
                      $isDesktop ? 'w-full h-auto md:w-auto md:h-[calc(100%-1rem)] border border-solid border-stone-900 rounded-b-lg' : ''
                    ]), ' ') ?>"
            src="<?= $image->thumbhashUri() ?>"
            data-srcset="<?= $image->srcset() ?>"
            data-sizes="auto"
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            style="aspect-ratio: <?= $image->width() ?>/<?= $image->height() ?>"
            alt="<?= $image->alt()->or('')->escape() ?>">
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <?= $slot ?>
</div>