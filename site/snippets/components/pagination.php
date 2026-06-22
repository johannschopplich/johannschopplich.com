<?php

/** @var \Kirby\Cms\Pagination $pagination */

?>
<nav class="flex justify-center text-xl font-heading font-600">
  <?php if ($pagination->hasPrevPage()): ?>
    <a href="<?= $pagination->prevPageUrl() ?>" class="i-tabler-arrow-left mr-auto hover:text-primary-accent" aria-label="Previous page"></a>
  <?php else: ?>
    <span class="i-tabler-arrow-left mr-auto text-contrast-low cursor-not-allowed" aria-disabled="true"></span>
  <?php endif ?>

  <div class="flex gap-1">
    <span aria-hidden="true">J</span>

    <?php foreach ($pagination->range(6) as $r): ?>
      <a
        href="<?= $pagination->pageUrl($r) ?>"
        class="group flex flex-col items-center"
        aria-label="Go to page <?= $r ?>"
        <?php e($pagination->page() === $r, 'aria-current="page"') ?>>
        <span class="<?= $pagination->page() === $r ? 'text-primary-accent' : 'text-current' ?>">o</span>
        <span class="mt-2 text-base font-500 <?= $pagination->page() === $r ? 'text-current' : 'text-primary-accent group-hover:text-underline' ?>">
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
    <a href="<?= $pagination->nextPageUrl() ?>" class="i-tabler-arrow-right ml-auto hover:text-primary-accent" aria-label="Next page"></a>
  <?php else: ?>
    <span class="i-tabler-arrow-right ml-auto text-contrast-lower cursor-not-allowed" aria-disabled="true"></span>
  <?php endif ?>
</nav>
