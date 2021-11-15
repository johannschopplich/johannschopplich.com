<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<div class="section">
  <?php $blocks = $page->text()->toBlocks() ?>
  <?php if ($blocks->isNotEmpty()): ?>
    <div class="container-fluid max-w-[60ch] pb-13">
      <section class="content">
        <h1 class="sr-only">
          <?= $page->title()->escape() ?>
        </h1>
        <?= $blocks ?>
      </section>
    </div>
  <?php endif ?>

  <?php snippet('photography/masonry', ['query' => $page->gallery()->toFiles()]) ?>
</div>

<?php snippet('footer') ?>
