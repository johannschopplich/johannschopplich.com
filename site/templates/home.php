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
              <a href="<?= $social->url() ?>" target="_blank" style="--un-color-link: var(--un-color-contrast-low); --un-color-link-hover: var(--un-color-text);">
                <span class="size-[1.5em] <?= $social->icon() ?>"></span>
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

<div class="content max-w-screen-lg">
  <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>

  <h2 class="title text-primary-500 text-2xl text-center mb-lg un-dark:text-primary-400">
    <?= t('articles.latest') ?>
  </h2>
</div>

<div class="border-y border-y-solid un-dark:border-contrast-lower">
  <div class="content max-w-screen-lg">
    <div class="overflow-hidden">
      <?php snippet('articles', [
        'query' => $kirby->collection('articles')->paginate(4)
      ]) ?>
    </div>
  </div>
</div>

<div class="content max-w-screen-lg">
  <div class="text-center mt-lg">
    <a href="<?= page('blog')->url() ?>" class="cta-button">
      <?= t('articles.more') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
    </a>
  </div>
</div>

<div class="content max-w-screen-lg">
  <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>

  <h2 class="title text-primary-500 text-2xl text-center mb-lg un-dark:text-primary-400">
    <?= t('home.references') ?>
  </h2>
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

<div class="content max-w-screen-lg">
  <div class="text-center mt-lg">
    <a href="<?= page('work')->url() ?>" class="cta-button">
      <?= page('work')->title()->escape() ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
    </a>
  </div>
</div>

<?php if ($photography = page('photography')): ?>
  <?php $query = $photography
    ->files()
    ->shuffle()
    // ->filterBy('ratio', '>=', '1')
    ->limit(4)
  ?>

  <div class="content max-w-screen-lg">
    <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>

    <h2 class="title text-primary-500 text-2xl text-center mb-lg un-dark:text-primary-400">
      <?= t('photography') ?>
    </h2>
  </div>

  <?php snippet('components/slider', ['query' => $query, 'links' => false], slots: true) ?>
  <div class="pr-xs shrink-0 snap-end snap-always">
    <div class="relative h-$cell-base aspect-square flex items-center justify-center bg-contrast-lowest border border-transparent hover:border-current md:h-$cell-md">
      <a href="<?= $query->first()->parent()->url() ?>" class="cta-button hover:text-theme-base">
        <span class="absolute inset-0" aria-hidden="true"></span>
        <?= t('photography.morePhotos') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
      </a>
    </div>
  </div>
  <?php endsnippet() ?>

  <div class="content max-w-screen-lg">
    <div class="text-center mt-lg">
      <a href="<?= $photography->url() ?>" class="cta-button">
        <?= t('photography.morePhotos') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
      </a>
    </div>
  </div>
<?php endif ?>

<div class="content max-w-screen-lg">
  <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>

  <h2 class="title text-primary-500 text-2xl text-center mb-lg un-dark:text-primary-400">
    <?= t('generic.contact') ?>
  </h2>
</div>

<div class="border-t border-t-solid un-dark:border-contrast-lower">
  <div class="content pt-lg">
    <div class="prose max-w-prose text-contrast-medium">
      <?= $page->contactText()->toBlocks() ?>
      <div class="space-y-2">
        <?php foreach ($page->contact()->toStructure() as $item): ?>
          <div class="flex gap-xl justify-between">
            <p><?= $item->title()->escape() ?></p>
            <p><?= $item->link()->permalinksToUrls() ?></p>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>

<div class="pb-8xl"></div>
<?php endsnippet() ?>