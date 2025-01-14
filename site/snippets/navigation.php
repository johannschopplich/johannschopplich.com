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
    <a href="<?= $item->url() ?>"
      class="flex items-center md:before:content-empty md:before:w-[0.5em] md:before:h-[0.5em] md:before:rounded-full md:before:bg-contrast-lower md:before:mr-1 lg:before:mr-2
             md:hover:text-current md:hover:before:bg-primary md:focus:before:bg-primary
             md:[&[aria-current]]:before:bg-current
             max-md:hover:underline-default max-md:[&[aria-current]]:underline-default
             font-500"
      <?php e($item->isOpen(), 'aria-current="page"') ?>>
      <?= $item->title() ?>
    </a>
  <?php endforeach ?>
</nav>