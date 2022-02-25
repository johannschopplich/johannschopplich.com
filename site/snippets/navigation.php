<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<nav class="flex flex-wrap gap-x-lg items-center font-heading">
  <a href="<?= $site->url() ?>" class="basis-full sm:basis-auto mr-auto font-600" aria-label="<?= t('home') ?>"<?php e($page->isHomePage(), ' aria-current="page"') ?>>
    <?= $site->title()->escape() ?>
  </a>

  <?php foreach ($site->children()->listed() as $item): ?>
    <a href="<?= $item->url() ?>" class="navbar-link flex items-center font-500"<?php e($item->isOpen(), ' aria-current="page"') ?>>
      <?= $item->title() ?>
    </a>
  <?php endforeach ?>
</nav>
