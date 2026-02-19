<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$details = $page->details()->toStructure();

?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-5xl md:pt-8xl">
  <div class="px-lg md:px-gutter">
    <h1 class="headline max-w-prose">
      <?= $page->title()->escape() ?>
    </h1>
  </div>

  <div class="pl-lg pb-5xl md:pl-gutter">
    <div class="flex gap-2 items-center mt-1 after:content-empty after:flex-1 after:h-[1px] after:border-b after:border-dotted after:border-contrast-soft">
      <p class="subtext text-contrast-medium shrink-0 max-md:text-sm">
        <?= $page->subtitle()->escape() ?>
      </p>
    </div>
  </div>


  <?php snippet('components/carousel', [
    'query' => $page->gallery()->toFiles(),
    'height' => $page->galleryHeight()->value()
  ]) ?>
</div>

<div class="pb-8xl">
  <div class="<?= trim(implode(' ', [
    'px-lg md:px-gutter',
    $details->isNotEmpty() ? 'lg:grid lg:grid-cols-[minmax(0,var(--container-prose))_auto] lg:gap-5xl' : ''
  ]), ' ') ?>">
    <?php if ($details->isNotEmpty()): ?>
      <aside class="cross-box bg-contrast-lowest py-5 px-4 sm:px-5 mb-5xl lg:sticky lg:top-16 lg:self-start lg:order-2 lg:mb-0 lg:max-w-sm">
        <dl class="flex flex-col gap-3 m-0">
          <?php foreach ($details as $detail): ?>
            <div>
              <dt class="caption text-contrast-medium"><?= $detail->label()->escape() ?></dt>
              <dd class="prose text-sm">
                <?php if ($detail->text()->isNotEmpty()): ?>
                  <?= $detail->text() ?>
                <?php endif ?>
                <?php if ($file = $detail->file()->toFile()): ?>
                  <a href="<?= $file->url() ?>" target="_blank" rel="noopener"><?= $file->filename() ?></a>
                <?php endif ?>
              </dd>
            </div>
          <?php endforeach ?>
        </dl>
      </aside>
    <?php endif ?>

    <div class="max-w-prose">
      <?php if ($page->text()->isNotEmpty()): ?>
        <div class="prose <?php e($details->isNotEmpty(), 'is-contained') ?> mb-5xl">
          <?= $page->text()->toBlocks() ?>
        </div>
      <?php endif ?>

      <div class="pt-lg border-t border-t-solid border-contrast-low dark:border-contrast-lower">
        <div class="-ml-1">
          <a href="<?= $page->parent()->url() ?>" class="action-link link-default">
            <span class="i-tabler-arrow-left mr-1" aria-hidden="true"></span>
            <span><?= t('project.allWorks') ?></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endsnippet() ?>
