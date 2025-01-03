<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="content relative max-w-screen-lg pt-5xl md:pt-8xl">
  <div class="prose">
    <?php foreach ($page->text()->toBlocks() as $block): ?>
      <?php /** @var \Kirby\Cms\Block $block */ ?>
      <?php if ($block->type() === 'heading' && $block->content()->level()->value() === 'h1'): ?>
        <h1 class="headline" style="--un-decoration-offset: max(2px, 0.1em);">
          <?php
          $words = explode(' ', $block->text());
          $lastWord = array_pop($words);
          $rest = implode(' ', $words);
          echo $rest;
          ?>
          <span class="whitespace-nowrap">
            <?= $lastWord ?>
            <a
              href="<?= page('about')->url() ?>"
              class="relative inline-block h-[0.825em] select-none touch-manipulation [&>svg]:h-full [&>svg]:w-auto"
              style="--un-animated-duration: 800ms"
              data-sticker="svg">
              <?= icon('johann.svg') ?>
              <span class="sr-only"><?= page('about')->title()->escape() ?></span>
            </a>
          </span>
        </h1>

        <?php if ($page->socialLinks()->toStructure()->isNotEmpty()): ?>
          <div class="flex gap-lg">
            <?php foreach ($page->socialLinks()->toStructure() as $social): ?>
              <a href="<?= $social->url() ?>" target="_blank" class="hover:text-current" style="--un-color-link: var(--un-color-contrast-low)">
                <span class="size-[1.75em] <?= $social->icon() ?>"></span>
                <span class="sr-only"><?= $social->platform()->escape() ?></span>
              </a>
            <?php endforeach ?>
          </div>
        <?php endif ?>
      <?php else: ?>
        <div class="max-w-prose text-contrast-medium">
          <?= $block ?>
        </div>
      <?php endif ?>
    <?php endforeach ?>
  </div>
</div>

<div class="section-divider"></div>

<div class="content mb-lg">
  <div class="max-w-screen-lg flex gap-xl justify-between items-center">
    <h2 class="title text-primary-500 text-2xl un-dark:text-primary-400">
      <?= t('articles.latest') ?>
    </h2>

    <a href="<?= page('blog')->url() ?>" class="cta-button link-default">
      <span class="sm:hidden"><?= t('generic.more') ?></span>
      <span class="hidden sm:inline"><?= t('articles.more') ?></span>
      <span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
    </a>
  </div>
</div>

<div class="border-y border-y-solid un-dark:border-contrast-lower">
  <div class="content">
    <div class="max-w-screen-lg overflow-hidden">
      <?php snippet('articles', [
        'query' => $kirby->collection('articles')->paginate(4)
      ]) ?>
    </div>
  </div>
</div>

<?php /*
<div class="section-divider"></div>

<div class="content">
  <div class="max-w-screen-lg">
    <h2 class="title text-primary-500 text-2xl mb-lg un-dark:text-primary-400">
      <?= t('home.references') ?>
    </h2>
  </div>
</div>

<div
  class="relative py-xl [--gap:3rem] [--duration:50s] md:[--gap:6rem] md:[--duration:70s]"
  style="--un-color-link-hover: currentColor">
  <?php snippet('components/marquee', slots: true) ?>
  <?php foreach ($page->logos()->toFiles()->filter('extension', 'svg') as $logo): ?>
    <a href="<?= $logo->website() ?>" target="_blank" class="block h-[clamp(3rem,5vw,4rem)] transition-opacity duration-100 [&>svg]:h-full [&>svg]:w-auto [@media(hover:hover)]:group-has-[a:hover]:not-hover:opacity-50">
      <?= $logo->asset()->read() ?>
      <span class="sr-only">
        <?= $logo->brand()->escape() . ' ' . t('generic.uses')  . ' ' . $logo->usage() ?>
      </span>
    </a>
  <?php endforeach ?>
  <?php endsnippet() ?>

  <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(to right, var(--un-color-background) 0%, transparent 5%, transparent 95%, var(--un-color-background) 100%)"></div>
</div>
*/ ?>

<?php if ($photography = page('photography')): ?>
  <?php $query = $photography
    ->files()
    ->shuffle()
    // ->filterBy('ratio', '>=', '1')
    ->limit(4)
  ?>

  <div class="section-divider"></div>

  <div class="content mb-3xl">
    <div class="max-w-screen-lg flex gap-xl justify-between items-center">
      <h2 class="title text-primary-500 text-2xl un-dark:text-primary-400">
        <?= t('photography') ?>
      </h2>

      <a href="<?= $photography->url() ?>" class="cta-button link-default">
        <span class="sm:hidden"><?= t('generic.more') ?></span>
        <span class="hidden sm:inline"><?= t('photography.more') ?></span>
        <span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
      </a>
    </div>
  </div>

  <?php snippet('components/slider', ['query' => $query, 'links' => false], slots: true) ?>
  <div class="pr-xs shrink-0 snap-end snap-always">
    <div class="relative h-$cell-base aspect-square flex items-center justify-center bg-contrast-lowest border border-transparent hover:border-current md:h-$cell-md">
      <a href="<?= $query->first()->parent()->url() ?>" class="cta-button link-default">
        <span class="absolute inset-0" aria-hidden="true"></span>
        <?= t('photography.more') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
      </a>
    </div>
    <?php endsnippet() ?>
  </div>
<?php endif ?>

<div class="pb-8xl"></div>
<?php endsnippet() ?>