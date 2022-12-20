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
  style="--cell: <?= $heightMap[$height ?? 'loose'] ?>"
>
  <?php foreach ($query as $image): ?>
    <?php
      /** @var \Kirby\Cms\File $image */
      $settings = $image->gallery()->toObject();
      $mockup = $settings->mockup()->or('none')->value();
      $tag = ($hasLink = $settings->link()->isNotEmpty()) ? 'a' : 'div';
      ?>
    <<?= $tag . attr([
      'class' => 'shrink-0 snap-center snap-always first:snap-start',
      'href' => $settings->link()->value(),
      'target' => $hasLink ? '_blank' : null,
      'rel' => $hasLink ? 'noopener' : null
    ], ' ') ?>>
      <?php if ($mockup !== 'none'): ?>
        <div class="h-$cell bg-$bg p-3xl md:p-5xl relative" style="
          --bg: <?= $settings->bgColor()->or('var(--du-color-contrast-lower)') ?>;
        ">
        <?php if ($mockup === 'mobile'): ?>
          <div class="inset-l-1/2 -ml-1/8 md:bottom-5xl bottom-3xl absolute h-[2px] w-1/4 translate-y-[-3px] rounded-full bg-zinc-900"></div>
        <?php elseif ($mockup === 'desktop'): ?>
          <div class="top-3xl inset-x-3xl md:top-5xl md:inset-x-5xl absolute flex h-4 translate-y-[-1rem] items-center gap-1 rounded-t-lg bg-zinc-900 px-2">
            <?php foreach (range(1, 3) as $i): ?>
              <div class="bg-contrast-low h-1.5 w-1.5 rounded-full"></div>
            <?php endforeach ?>
          </div>
        <?php endif ?>
      <?php endif ?>

      <?php snippet('shortcuts/img', [
        'file' => $image,
        'class' => $mockup === 'mobile'
          ? 'h-full w-auto rounded-xl border border-zinc-900 object-contain'
          : ($mockup === 'desktop'
            ? 'h-full w-auto rounded-b-lg border border-zinc-900 object-contain'
            : 'h-$cell max-w-screen w-auto object-contain'),
        'zoomable' => $settings->link()->isEmpty() ? true : $zoomable ?? null
      ]) ?>

      <?php if ($mockup !== 'none'): ?>
        </div>
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
