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
      <path d="M143.605 75.31c1.866 10.529 3.469 24.223 3.33 39.69h93.678v38h-97.722c-7.43 35.138-26.624 73.184-69.84 102.621l-24.77-36.365c27.878-18.99 42.373-42.805 49.27-66.256H37.709v-38h65.298c.123-11.993-1.123-22.967-2.726-32.01z" />
      <circle cx="111" cy="25" r="25" />
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
