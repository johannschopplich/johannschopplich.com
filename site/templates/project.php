<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
  <div class="pt-8xl pb-5xl space-y-5xl">
    <div class="content max-w-prose">
      <p class="text-contrast-medium">
        <?= $page->subtitle()->escape() ?>
      </p>

      <h1 class="headline">
        <?= $page->title()->escape() ?>
      </h1>

      <?php /*
      <div class="mt-xs">
        <?= asset('assets/img/article-spacer.svg')->read() ?>
      </div>
      */ ?>
    </div>

    <?php if ($page->galleryType()->value() === 'slider'): ?>
      <?php snippet('components/slider', [
        'query' => $page->gallery()->toFiles(),
        'height' => $page->galleryHeight()->value(),
        'links' => false
      ]) ?>
    <?php endif ?>
  </div>

  <?php $isMasonry = $page->galleryType()->value() === 'masonry' ?>
  <div class="pb-8xl <?= e($isMasonry, 'lg:flex lg:flex-wrap') ?>">
    <?php if ($isMasonry): ?>
      <div class="mb-5xl w-auto flex-1 lg:max-w-prose lg:order-last">
        <?php snippet('components/masonry', [
          'query' => $page->gallery()->toFiles(),
          'width' => 'min(15rem, 50vw)'
        ]) ?>
      </div>
    <?php endif ?>

    <div class="content w-full max-w-prose">
      <?php if ($page->text()->isNotEmpty()): ?>
        <div class="prose is-article mb-5xl">
          <?= $page->text()->toBlocks() ?>
        </div>
      <?php endif ?>

      <div class="pt-lg border-t border-t-solid du-dark:border-contrast-lower">
        <div class="-ml-1">
          <a href="<?= $page->parent()->url() ?>" class="action-button">
            <span class="i-bx-left-arrow-alt mr-1" aria-hidden="true"></span><?= t('project.allWorks') ?>
          </a>
        </div>
      </div>
    </div>
  </div>
<?php endsnippet() ?>
