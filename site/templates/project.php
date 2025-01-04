<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$isMasonry = $page->galleryType()->value() === 'masonry';
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-5xl md:pt-8xl">
  <div class="content">
    <h1 class="headline max-w-prose">
      <?= $page->title()->escape() ?>
    </h1>
  </div>

  <div class="content-l pb-5xl">
    <div class="flex gap-1 items-center mt-1 after:content-empty after:flex-1 after:h-[1px] after:bg-current un-dark:after:bg-contrast-lower">
      <p class="shrink-0 text-sm font-500">
        <?= $page->subtitle()->escape() ?>
      </p>
    </div>
  </div>

  <?php if ($page->galleryType()->value() === 'slider'): ?>
    <?php snippet('components/slider', [
      'query' => $page->gallery()->toFiles(),
      'height' => $page->galleryHeight()->value()
    ]) ?>
  <?php endif ?>
</div>

<div class="pb-8xl <?= e($isMasonry, 'lg:flex lg:flex-wrap lg:gap-5xl') ?>">
  <?php if ($isMasonry): ?>
    <div class="mb-5xl w-auto flex-1 lg:max-w-prose lg:order-last">
      <?php snippet('components/masonry', [
        'query' => $page->gallery()->toFiles(),
        'width' => 'min(15rem, 50vw)'
      ]) ?>
    </div>
  <?php endif ?>

  <div class="content">
    <div class="max-w-prose">
      <?php if ($page->text()->isNotEmpty()): ?>
        <div class="prose is-article mb-5xl">
          <?= $page->text()->toBlocks() ?>
        </div>
      <?php endif ?>

      <div class="pt-lg border-t border-t-solid un-dark:border-contrast-lower">
        <div class="-ml-1">
          <a href="<?= $page->parent()->url() ?>" class="cta-button link-default">
            <span class="i-bx-left-arrow-alt mr-1" aria-hidden="true"></span>
            <span><?= t('project.allWorks') ?></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endsnippet() ?>