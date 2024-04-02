<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
/** @var string|null $height */
/** @var bool|null $links */

$heightMap = [
  'tight' => 'clamp(40svh, 50vw, 25svh)',
  'normal' => 'clamp(40svh, 50vw, 50svh)',
  'loose' => 'clamp(40svh, 50vw, 75svh)'
];

?>

<div
  class="w-full flex gap-xs snap-x snap-mandatory overflow-x-auto py-px"
  style="--cell: <?= $heightMap[$height ?? 'loose'] ?>"
>
  <?php foreach ($query as $image): ?>
    <?php
    /** @var \Kirby\Cms\File $image */
    $settings = $image->gallery()->toObject();
    $mockup = $settings->mockup()->or('none')->value();
    $target = $links && $settings->link()->isNotEmpty() ? $settings->link()->value() : null;
    $tag = $target ? 'a' : 'div';
    $isDocument = $mockup === 'document';
    $isMobile = $mockup === 'mobile';
    $isDesktop = $mockup === 'desktop';
    ?>
    <<?= $tag . ' ' . attr([
      'class' => trim(implode(' ', [
        'shrink-0 snap-center snap-always first:snap-start',
        $target ? 'group' : '',
      ]), ' '),
      'href' => $target,
      'target' => $target ? '_blank' : null
    ]) ?>>
      <div
        class="<?= trim(implode(' ', [
          'group-hover:ring group-hover:ring-theme-base',
          $mockup !== 'none' ? 'relative h-$cell bg-$bg' : '',
          ($isMobile || $isDocument) ? 'px-5xl py-3xl md:px-8xl md:py-5xl xl:px-[9rem]' : '',
          $isDesktop ? 'flex flex-col p-3xl md:p-5xl' : ''
        ]), ' ') ?>"
        style="--bg: <?= $settings->bgColor()->or('var(--du-color-contrast-lower)') ?>"
      >
        <?php if ($isMobile): ?>
          <div class="absolute left-1/2 bottom-3xl h-[1px] w-[15%] bg-stone-900 ml-[-7.5%] rounded-full translate-y-[-4px] md:bottom-5xl md:h-[2px] md:translate-y-[-6px]"></div>
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
            'object-contain w-auto group-hover:opacity-80',
            $mockup === 'none' ? 'h-$cell max-w-[calc(100vw-2.25rem)]' : 'border border-solid border-stone-900',
            $isMobile ? 'h-full rounded-md md:rounded-xl' : '',
            $isDocument ? 'h-full' : '',
            $isDesktop ? 'h-[calc(100%-1rem)] rounded-b-lg' : ''
          ]), ' ') ?>"
          src="<?= $image->thumbhashUri() ?>"
          data-srcset="<?= $image->srcset() ?>"
          data-sizes="auto"
          width="<?= $image->width() ?>"
          height="<?= $image->height() ?>"
          style="<?= $mockup === 'none' ? 'aspect-ratio:' . $image->width() . '/' . $image->height() : '' ?>"
          alt="<?= $image->alt()->or('')->escape() ?>"
        >
      </div>
    </<?= $tag ?>>
  <?php endforeach ?>

  <?= $slot ?>
</div>
