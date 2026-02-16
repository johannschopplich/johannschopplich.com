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
    "
    <?php e($page->isHomePage(), ' aria-current="page"') ?>
    aria-label="<?= $site->title()->escape() ?>"
  >
    <svg class="size-[1.25em]" viewBox="0 0 256 256" fill="currentColor" style="overflow: visible; stroke: var(--un-dithered-to, var(--un-color-background)); stroke-width: 44; paint-order: stroke fill;" aria-hidden="true">
      <path d="M143.86 77.184c.901 10.29 2.081 23.271 2.005 37.816h94.748v38h-98.26c-6.709 34.892-24.858 72.751-69.427 102.657L48.41 219.12c28.748-19.29 42.607-42.564 48.847-66.12H37.708v-38h64.136c.059-11.986-.898-23.494-1.816-33.982zM116 0c13.807 0 25 11.193 25 25s-11.193 25-25 25-25-11.193-25-25 11.193-25 25-25" />
    </svg>
  </a>

  <?php foreach ($site->children()->listed() as $item): ?>
    <a
      href="<?= $item->url() ?>"
      class="
        bg-theme-background halftone-bg overline px-1 py-0.5
        <?php /* [@supports(text-box:trim-both_cap_alphabetic)]:[text-box:trim-both_cap_alphabetic] [@supports(text-box:trim-both_cap_alphabetic)]:py-2 */ ?>
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
