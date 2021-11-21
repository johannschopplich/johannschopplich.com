<?php /** @var \Kirby\Cms\Pagination $pagination */ ?>
<div class="due-py-m">
  <div class="due-py-xl due-container-xl-full bg-lowest">
    <div class="due-container-lg">
      <nav class="flex justify-center due-text-3 font-700" style="--du-color-link: var(--du-color-accent); --du-color-link-hover: var(--du-color-text);">
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
