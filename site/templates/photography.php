<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<div class="section">
  <?php $blocks = $page->text()->toBlocks() ?>
  <?php if ($blocks->isNotEmpty()): ?>
    <div class="container max-w-[60ch] mx-0 pb-13">
      <section class="content">
        <h1 class="sr-only">
          <?= $page->title() ?>
        </h1>
        <?= $blocks ?>
      </section>
    </div>
  <?php endif ?>

  <?php snippet('photography/masonry', ['query' => $page->gallery()->toFiles()]) ?>
</div>

<?php snippet('footer') ?>
