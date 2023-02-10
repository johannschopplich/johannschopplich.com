<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $height */
/** @var bool|null $links */

$heightMap = [
  'tight' => 'clamp(36svh, 50vw, 25svh)',
  'normal' => 'clamp(36svh, 50vw, 50svh)',
  'loose' => 'clamp(36svh, 50vw, 75svh)'
];

?>
<div data-slider>
  <div
    class="flex w-full snap-x snap-mandatory gap-xs overflow-x-auto"
    style="--cell: <?= $heightMap[$height ?? 'loose'] ?>"
  >
    <?php foreach ($query as $image): ?>
      <?php
      /** @var \Kirby\Cms\File $image */
      $settings = $image->gallery()->toObject();
      $mockup = $settings->mockup()->or('none')->value();
      $hasLink = ($links ?? true) && $settings->link()->isNotEmpty();
      $tag = $hasLink ? 'a' : 'div';
      ?>
      <<?= $tag . attr([
        'class' => 'swiper-slide shrink-0 snap-center snap-always first:snap-start',
        'href' => $hasLink ? $settings->link()->or(null)->value() : null,
        'target' => $hasLink ? '_blank' : null,
        'rel' => $hasLink ? 'noopener' : null
      ], ' ') ?>>
        <div
          data-animere-slide="fadeInLeft"
          data-animere-slide-duration="500ms"
          data-animere-slide-delay="<?= $image->indexOf($query) * 50 + 50 . 'ms' ?>"
        >
          <?php if ($mockup !== 'none'): ?>
            <div
              class="h-$cell bg-$bg p-3xl md:p-5xl relative<?php e($mockup === 'desktop', ' flex flex-col') ?>"
              style="--bg: <?= $settings->bgColor()->or('var(--du-color-contrast-lower)') ?>"
            >
            <?php if ($mockup === 'mobile'): ?>
              <div class="inset-l-1/2 -ml-1/8 md:bottom-5xl bottom-3xl absolute h-[4px] w-1/4 translate-y-[-6px] rounded-full bg-zinc-900"></div>
            <?php elseif ($mockup === 'desktop'): ?>
              <div class="flex h-4 items-center gap-1 rounded-t-lg border-x border-x-solid border-t border-t-solid border-zinc-900 px-1.5">
                <?php foreach (range(1, 3) as $i): ?>
                  <div class="border border-solid border-zinc-900 h-1.5 w-1.5 rounded-full"></div>
                <?php endforeach ?>
              </div>
            <?php endif ?>
          <?php endif ?>

          <img
            src="<?= $image->blurhashUri() ?>"
            class="<?= $mockup === 'mobile'
              ? 'h-full w-auto rounded-xl border-2 border-solid border-zinc-900 object-contain'
              : ($mockup === 'desktop'
                ? 'h-[calc(100%-1rem)] w-auto rounded-b-lg border border-solid border-zinc-900 object-contain'
                : 'h-$cell max-w-screen w-auto object-contain') ?>"
            srcset="<?= $image->srcset() ?>"
            data-auto-sizes
            width="<?= $image->width() ?>"
            height="<?= $image->height() ?>"
            alt="<?= $image->alt()->or('')->escape() ?>"
          >

          <?php if ($mockup !== 'none'): ?>
            </div>
          <?php endif ?>
        </div>
      </<?= $tag ?>>
    <?php endforeach ?>

    <?php if ($page->isHomePage()): ?>
      <div class="swiper-slide pr-xs shrink-0 snap-end snap-always">
        <div class="h-$cell relative flex w-[min(65vw,25rem)] items-center justify-center border border-solid">
          <a href="<?= $query->first()->parent()->url() ?>" class="action-button">
            <span class="absolute inset-0" aria-hidden="true"></span>
            <?= t('photography.morePhotos') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
          </a>
        </div>
      </div>
    <?php endif ?>
  </div>
</div>
