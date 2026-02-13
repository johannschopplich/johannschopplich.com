<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<nav class="flex items-center gap-x-3 flex-wrap" style="--un-decoration-thickness: max(2px, 0.125em)">
  <a
    href="<?= $site->url() ?>"
    class="
      inline-flex items-center bg-theme-background halftone-bg pl-0.5 pr-1 py-0.75
      [--un-dithered-from:var(--un-color-contrast-soft)] hover:[--un-dithered-from:currentcolor]
      aria-[current]:[--un-dithered-from:var(--un-color-contrast-high)]
    "
    <?php e($page->isHomePage(), ' aria-current="page"') ?>
    aria-label="<?= $site->title()->escape() ?>"
  >
    <svg class="size-[1.25em]" viewBox="0 0 256 256" fill="currentColor" style="overflow: visible; stroke: var(--un-dithered-to, var(--un-color-background)); stroke-width: 64; paint-order: stroke fill;" aria-hidden="true">
      <path d="M95.302 207.282c0 30.72-16.446 48.717-48.407 48.717-4.965 0-11.171-.31-14.895-1.551v-30.72c2.172.31 5.586.931 8.378.931 11.171 0 16.446-5.275 16.446-17.687V62.991h38.477l.001 144.291ZM143.604 160.426c3.103 9.93 11.792 15.205 23.273 15.205 11.791 0 19.549-4.654 19.549-13.653 0-6.206-4.344-9.309-13.963-11.791l-27.927-6.206c-21.1-4.965-35.995-15.825-35.995-39.098 0-26.996 24.204-44.994 55.234-44.994 35.064 0 54.303 16.756 58.957 42.822h-36.616c-2.482-7.757-8.999-13.653-21.721-13.653-9.309 0-18.308 4.344-18.308 13.033 0 5.585 3.724 8.688 12.412 10.86l28.548 6.516c24.204 5.896 36.926 19.239 36.926 40.339 0 28.237-24.514 44.994-56.785 44.994-33.823 0-57.716-16.136-61.75-44.373l38.166-.001ZM222.731 0H56.824v38.477h165.907V0Z" />
    </svg>
  </a>

  <?php foreach ($site->children()->listed() as $item): ?>
    <a
      href="<?= $item->url() ?>"
      class="
        bg-theme-background halftone-bg overline px-1 py-0.5
        [@supports(text-box:trim-both_cap_alphabetic)]:[text-box:trim-both_cap_alphabetic] [@supports(text-box:trim-both_cap_alphabetic)]:py-2
        [--un-dithered-from:var(--un-color-contrast-soft)] hover:[--un-dithered-from:currentcolor] hover:text-underline hover:decoration-primary-accent
        aria-[current]:[--un-dithered-from:var(--un-color-contrast-high)] aria-[current]:text-underline aria-[current]:decoration-primary-accent
      "
      <?php e($item->isOpen(), 'aria-current="page"') ?>
    >
      <?= $item->title() ?>
    </a>
  <?php endforeach ?>

  <div class="flex items-center ml-auto overline max-sm:hidden">
    <?php foreach (($languages = $kirby->languages()) as $language): ?>
      <?php if (!$language->isFirst($languages)): ?>
        <span class="text-contrast-soft px-1 select-none">/</span>
      <?php endif ?>
      <?php if ($language->code() === $kirby->languageCode()): ?>
        <a
          href="<?= $page->urlForLanguage($language->code()) ?>"
          hreflang="<?= $language->code() ?>"
          aria-current="page"
          class="no-underline"
          aria-label="<?= $language->name() ?>"
        >[<?= $language->code() ?>]</a>
      <?php else: ?>
        <a
          href="<?= $page->urlForLanguage($language->code()) ?>"
          hreflang="<?= $language->code() ?>"
          class="link-default text-contrast-medium hover:text-contrast-higher"
          aria-label="<?= $language->name() ?>"
        ><?= $language->code() ?></a>
      <?php endif ?>
    <?php endforeach ?>
  </div>
</nav>
