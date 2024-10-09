<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<nav class="gap-x-lg font-heading flex flex-wrap items-center">
  <div class="mr-auto basis-full sm:basis-auto">
    <a href="<?= $site->url() ?>" class="font-600" <?php e($page->isHomePage(), ' aria-current="page"') ?>>
      <?= $site->title()->escape() ?>
    </a>
  </div>

  <?php foreach ($site->children()->listed() as $item): ?>
    <a href="<?= $item->url() ?>" class="navbar-link font-500" <?php e($item->isOpen(), ' aria-current="page"') ?>>
      <?= $item->title() ?>
    </a>
  <?php endforeach ?>

  <div class="absolute right-lg top-1/2 -translate-y-1/2 sm:relative sm:right-0 sm:top-0 sm:h-$docsearch-height sm:min-w-$docsearch-width sm:translate-0 md:my-[-4px]" data-docsearch>
    <button type="button" class="DocSearch DocSearch-Button" aria-label="<?= t('docsearch.placeholder') ?>"><span class="DocSearch-Button-Container"><svg width="20" height="20" class="DocSearch-Search-Icon" viewBox="0 0 20 20">
          <path d="M14.386 14.386l4.0877 4.0877-4.0877-4.0877c-2.9418 2.9419-7.7115 2.9419-10.6533 0-2.9419-2.9418-2.9419-7.7115 0-10.6533 2.9418-2.9419 7.7115-2.9419 10.6533 0 2.9419 2.9418 2.9419 7.7115 0 10.6533z" stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg><span class="DocSearch-Button-Placeholder"><?= t('docsearch.placeholder') ?></span></span></button>
  </div>
</nav>