<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<div class="due-py-xl">
  <div class="due-container-md due-mb-xl">
    <p class="due-text-5 text-contrast-medium">
      <?= $page->subtitle()->escape() ?>
    </p>

    <h1 class="title due-text-hero font-bold lh-none">
      <?= $page->title()->escape() ?>
    </h1>

    <div class="due-mt-s">
      <?= asset('assets/img/article-spacer.svg')->read() ?>
    </div>
  </div>

  <div class="due-container-lg">
    <div class="space-y-5">
      <?php foreach ($files = $page->gallery()->toFiles() as $image): ?>
        <figure>
          <?php snippet('helpers/img', [
            'image' => $image,
            'zoomable' => true
          ]) ?>
        </figure>
      <?php endforeach ?>
    </div>
  </div>
</div>

<div class="due-py-xl">
  <div class="due-container-sm">
    <?php if ($page->text()->isNotEmpty()): ?>
      <div class="content due-mb-2xl">
        <?= $page->text()->kirbytext() ?>
      </div>
    <?php endif ?>

    <div class="text-center">
      <a href="<?= $page->parent()->url() ?>" class="button-text">
        <?= t('project.allWorks') ?>
      </a>
    </div>
  </div>
</div>
