<?php /** @var \Kirby\Cms\Pagination $pagination */ ?>
<nav
  class="flex justify-center text-xl leading-heading font-heading font-700"
  style="--du-color-link: var(--du-color-primary); --du-color-link-hover: var(--du-color-text);"
>
  <?php if ($pagination->hasPrevPage()): ?>
    <a href="<?= $pagination->prevPageUrl() ?>" class="mr-auto i-bx-left-arrow-alt" aria-label="Previous page"></a>
  <?php else: ?>
    <span class="mr-auto text-contrast-low i-bx-left-arrow-alt" aria-disabled="true"></span>
  <?php endif ?>

  <div class="flex space-x-1">
    <span aria-hidden="true">J</span>

    <?php foreach ($pagination->range(6) as $r): ?>
      <a
        href="<?= $pagination->pageUrl($r) ?>"
        class="group flex flex-col items-center<?php e($pagination->page() === $r, ' text-theme-base') ?>"
        aria-label="Go to page <?= $r ?>"
        <?php e($pagination->page() === $r, 'aria-current="page"') ?>
      >
        <span>o</span>
        <span class="mt-2 text-contrast-low text-base leading-normal font-500<?php e($pagination->page() === $r, ' text-theme-base') ?>">
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
    <a href="<?= $pagination->nextPageUrl() ?>" class="ml-auto i-bx-right-arrow-alt" aria-label="Next page"></a>
  <?php else: ?>
    <span class="ml-auto text-contrast-lower i-bx-right-arrow-alt" aria-disabled="true"></span>
  <?php endif ?>
</nav>
