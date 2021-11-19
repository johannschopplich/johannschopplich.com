<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<nav class="flex flex-wrap items-center" style="--du-color-link-hover: var(--du-color-accent);">
  <div class="mr-auto due-mr-m">
    <a href="<?= $site->url() ?>" class="navbar-logo" aria-label="<?= t('home') ?>"<?php e($page->isHomePage(), ' aria-current="page"') ?>>
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 185.6 247.5" class="inline h-[var(--du-text-3)]">
        <path d="M61.2,200.4c0,29.7-15.9,47.1-46.8,47.1c-4.8,0-10.8-0.3-14.4-1.5v-29.7c2.7,0.5,5.4,0.8,8.1,0.9c10.8,0,15.9-5.1,15.9-17.1 V60.9h37.2V200.4z" /><path d="M107.9,155.1c3,9.6,11.4,14.7,22.5,14.7c11.4,0,18.9-4.5,18.9-13.2c0-6-4.2-9-13.5-11.4l-27-6C88.4,134.4,74,123.9,74,101.4 c0-26.1,23.4-43.5,53.4-43.5c33.9,0,52.5,16.2,57,41.4H149c-2.4-7.5-8.7-13.2-21-13.2c-9,0-17.7,4.2-17.7,12.6 c0,5.4,3.6,8.4,12,10.5l27.6,6.3c23.4,5.7,35.7,18.6,35.7,39c0,27.3-23.7,43.5-54.9,43.5C98,198,74.9,182.4,71,155.1H107.9z" /><rect x="24" width="160.4" height="37.2" />
      </svg>
      <span class="ml-1 hidden sm:inline"><?= $site->title()->escape() ?></span>
    </a>
  </div>

  <div class="flex items-center space-x-3 md:space-x-4">
    <?php foreach ($site->children()->listed() as $item): ?>
      <a href="<?= $item->url() ?>" class="navbar-link"<?php e($item->isOpen(), ' aria-current="page"') ?>>
        <?= $item->title() ?>
      </a>
    <?php endforeach ?>

    <a href="https://www.instagram.com/johannschopplich/" class="i-mdi-instagram due-text-4 hidden sm:inline-block items-center"></a>

    <a href="https://github.com/johannschopplich" class="i-mdi-github due-text-4 hidden sm:inline-block"></a>
  </div>
</nav>
