<?php

/** @var \Kirby\Cms\Pagination $pagination */ ?>
<nav
  class="font-heading font-600 flex justify-center text-xl">
  <?php if ($pagination->hasPrevPage()): ?>
    <a href="<?= $pagination->prevPageUrl() ?>" class="mr-auto i-tabler-arrow-left hover:text-link-hover" aria-label="Previous page"></a>
  <?php else: ?>
    <span class="mr-auto text-contrast-low cursor-not-allowed i-tabler-arrow-left" aria-disabled="true"></span>
  <?php endif ?>

  <div class="flex gap-1">
    <span aria-hidden="true">J</span>

    <?php foreach ($pagination->range(6) as $r): ?>
      <a
        href="<?= $pagination->pageUrl($r) ?>"
        class="group flex flex-col items-center"
        aria-label="Go to page <?= $r ?>"
        <?php e($pagination->page() === $r, 'aria-current="page"') ?>>
        <span class="<?= $pagination->page() === $r ? 'text-link-hover' : 'text-current' ?>">o</span>
        <span class="text-base font-500 mt-2 <?= $pagination->page() === $r ? 'text-current' : 'text-link-hover group-hover:underline-default' ?>">
          <?= $r ?>
        </span>
      </a>
    <?php endforeach ?>

    <span aria-hidden="true">h</span>
    <span aria-hidden="true">a</span>
    <span aria-hidden="true">n</span>
    <span aria-hidden="true">n</span>
  </div>

  <?php if ($pagination->hasNextPage()): ?>
    <a href="<?= $pagination->nextPageUrl() ?>" class="ml-auto i-tabler-arrow-right hover:text-link-hover" aria-label="Next page"></a>
  <?php else: ?>
    <span class="ml-auto text-contrast-lower cursor-not-allowed i-tabler-arrow-right" aria-disabled="true"></span>
  <?php endif ?>
</nav>