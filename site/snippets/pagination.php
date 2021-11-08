<?php /** @var \Kirby\Cms\Pagination $pagination */ ?>
<div class="py-m">
  <div class="section container is-xl is-fullwidth bg-lowest">
    <div class="container is-lg">
      <nav class="articles-pagination">
        <?php if ($pagination->hasPrevPage()): ?>
          <a href="<?= $pagination->prevPageUrl() ?>" class="pagination-previous" aria-label="Previous page">←</a>
        <?php else: ?>
          <span class="text-contrast-lower" aria-disabled="true">←</span>
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
          <a href="<?= $pagination->nextPageUrl() ?>" class="pagination-next" aria-label="Next page">→</a>
        <?php else: ?>
          <span class="text-contrast-lower" aria-disabled="true">→</span>
        <?php endif ?>
      </nav>
    </div>
  </div>
</div>
