<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<nav class="grid grid-cols-[1fr_auto] items-center gap-x-lg sm:grid-cols-[auto_1fr_auto]" style="--un-decoration-thickness: max(2px, 0.125em)">
  <a
    href="<?= $site->url() ?>"
    class="font-heading font-600 link-default"
    <?php e($page->isHomePage(), ' aria-current="page"') ?>>
    <?= $site->title()->escape() ?>
  </a>

  <div class="flex items-center justify-end sm:order-last sm:ml-lg">
    <?php foreach ($kirby->languages() as $language): ?>
      <span class="inline-flex text-contrast-medium text-sm font-500 uppercase px-0.5 not-last:after:content-['/'] not-last:after:text-contrast-low not-last:after:pl-1">
        <a
          href="<?= $page->urlForLanguage($language->code()) ?>"
          hreflang="<?= $language->code() ?>"
          class="link-default text-contrast-medium"
          <?php e($language->code() === $kirby->languageCode(), 'style="--un-decoration-color: currentColor" aria-current="page"') ?>><?= $language->code() ?></a>
      </span>
    <?php endforeach ?>
  </div>

  <div class="col-span-full flex items-center gap-x-lg sm:col-span-1 sm:justify-end">
    <?php foreach ($site->children()->listed() as $item): ?>
      <a href="<?= $item->url() ?>"
        class="flex items-center text-sm font-500 uppercase bg-theme-background px-[0.25em] mx-[-0.25em]
               md:before:content-empty md:before:w-[0.5em] md:before:h-[0.5em] md:before:rounded-full md:before:bg-contrast-lower md:before:mr-1 lg:before:mr-2
               md:hover:text-current md:hover:before:bg-primary md:focus:before:bg-primary
               md:aria-[current]:before:bg-current
               max-md:hover:underline-default max-md:aria-[current]:underline-default"
        <?php e($item->isOpen(), 'aria-current="page"') ?>>
        <?= $item->title() ?>
      </a>
    <?php endforeach ?>
  </div>
</nav>
</nav>