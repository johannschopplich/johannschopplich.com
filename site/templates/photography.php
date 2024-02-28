<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
  <div class="pt-5xl pb-8xl md:pt-8xl">
    <?php $blocks = $page->text()->toBlocks() ?>
    <?php if ($blocks->isNotEmpty()): ?>
      <div class="content max-w-prose pb-5xl">
        <section class="prose">
          <h1 class="sr-only">
            <?= $page->title()->escape() ?>
          </h1>

          <?= $blocks ?>
        </section>
      </div>
    <?php endif ?>

    <?php snippet('components/masonry', [
      'query' => $page->gallery()->toFiles()
    ]) ?>
  </div>
<?php endsnippet() ?>
