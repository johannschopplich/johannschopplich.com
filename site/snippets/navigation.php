<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<nav class="gap-x-lg font-heading flex flex-wrap items-center">
  <div class="mr-auto basis-full sm:basis-auto">
    <a href="<?= $site->url() ?>" class="font-600 link-default" <?php e($page->isHomePage(), ' aria-current="page"') ?>>
      <?= $site->title()->escape() ?>
    </a>
  </div>

  <?php foreach ($site->children()->listed() as $item): ?>
    <a href="<?= $item->url() ?>" class="navbar-link font-500" <?php e($item->isOpen(), ' aria-current="page"') ?>>
      <?= $item->title() ?>
    </a>
  <?php endforeach ?>
</nav>
