<?php /** @var \Kirby\Cms\Pagination $pagination */ ?>
<div class="py-m">
  <div class="section container-xl-full bg-lowest">
    <div class="container-lg">
      <nav class="flex justify-center font-size-3 font-700" style="--color-link: var(--color-accent); --color-link-hover: var(--color-text);">
        <?php if ($pagination->hasPrevPage()): ?>
          <a href="<?= $pagination->prevPageUrl() ?>" class="mr-auto" aria-label="Previous page">←</a>
        <?php else: ?>
          <span class="mr-auto text-contrast-lower" aria-disabled="true">←</span>
        <?php endif ?>

        <div class="flex space-x-1">
          <span aria-hidden="true">J</span>

          <?php foreach ($pagination->range(6) as $r): ?>
            <a
              href="<?= $pagination->pageUrl($r) ?>"
              class="pagination-link"
              aria-label="Go to page <?= $r ?>"
              <?php e($pagination->page() === $r, 'aria-current="page"') ?>
            >
              <span>o</span>
              <span><?= $r ?></span>
            </a>
          <?php endforeach ?>

          <span aria-hidden="true">h</span>
          <span aria-hidden="true">a</span>
          <span aria-hidden="true">n</span>
          <span aria-hidden="true">n</span>
        </div>

        <?php if ($pagination->hasNextPage()): ?>
          <a href="<?= $pagination->nextPageUrl() ?>" class="ml-auto" aria-label="Next page">→</a>
        <?php else: ?>
          <span class="ml-auto text-contrast-lower" aria-disabled="true">→</span>
        <?php endif ?>
      </nav>
    </div>
  </div>
</div>
