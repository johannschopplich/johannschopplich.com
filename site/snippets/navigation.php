<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<nav class="gap-x-lg font-heading flex flex-wrap items-center">
  <div class="mr-auto basis-full sm:basis-auto">
    <a href="<?= $site->url() ?>" class="font-600" aria-label="<?= t('home') ?>"<?php e($page->isHomePage(), ' aria-current="page"') ?>>
      <?= $site->title()->escape() ?>
    </a>
  </div>

  <?php foreach ($site->children()->listed() as $item): ?>
    <a href="<?= $item->url() ?>" class="navbar-link font-500"<?php e($item->isOpen(), ' aria-current="page"') ?>>
      <?= $item->title() ?>
    </a>
  <?php endforeach ?>

  <div class="absolute right-lg top-1/2 -translate-y-1/2 sm:relative sm:right-0 sm:top-0 sm:translate-0" data-docsearch></div>
</nav>
