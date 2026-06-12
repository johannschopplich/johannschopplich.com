<?php

use Kirby\Toolkit\I18n;

$complementaryLanguage = $kirby
  ->languages()
  ->filter(fn ($language) => $language->code() !== $kirby->languageCode())
  ->first();
?>
<footer class="sticky top-100vh">
  <div class="px-lg pt-8xl pb-3xl bg-contrast-lowest border-y border-contrast-low md:px-gutter <?php /* xl:max-w-[calc(64rem+2*var(--un-content-gutter))] xl:border-r */ ?>">
    <div class="max-w-screen-lg">
      <div class="mb-8xl">
        <h3 class="title mb-3xl text-2xl">
          <?= t('generic.contact') ?>
          <span class="icon-inline ml-1 h-[1em] translate-y-[2px]">
            <?= icon('johann.svg') ?>
          </span>
        </h3>

        <ul>
          <?php foreach ($site->footerLinks()->toStructure() as $item): ?>
            <li class="group border-b border-dotted border-contrast-soft hover:border-solid hover:border-current">
              <a href="<?= $item->url() ?>" class="grid grid-cols-4 items-center py-sm sm:grid-cols-6 lg:grid-cols-9">
                <span class="overline col-start-1 col-end-4 text-contrast-medium sm:col-start-1 sm:col-end-3 lg:col-start-1 lg:col-end-4"><?= $item->category()->escape() ?></span>
                <span class="col-start-1 col-end-4 sm:col-start-3 sm:col-end-5 lg:col-start-4 lg:col-end-7"><?= $item->title()->escape() ?></span>
                <span class="col-start-4 col-end-4 justify-self-end transition -rotate-45 group-hover:rotate-0 sm:col-start-5 sm:col-end-7 lg:col-start-7 lg:col-end-10">
                  <span class="i-tabler-arrow-right" aria-hidden="true"></span>
                </span>
              </a>
            </li>
          <?php endforeach ?>
        </ul>
      </div>

      <div class="flex gap-lg justify-between">
        <a
          href="<?= $page->url($complementaryLanguage->code()) ?>"
          hreflang="<?= $complementaryLanguage->code() ?>"
          class="subtext link-default inline-flex items-center gap-2">
          <span class="icon" aria-hidden="true">
            <?= icon('earth.svg') ?>
          </span>
          <span>
            <?= I18n::template('languages.switch', null, ['language' => t('languages.' . $complementaryLanguage->code())]) ?>
          </span>
        </a>
        <button
          class="link-default inline-flex items-center gap-2 text-sm"
          data-theme-switcher>
          <span class="icon dark:hidden" aria-hidden="true"><?= icon('sun.svg') ?></span>
          <span class="font-500 dark:hidden"><?= t('theme.switch.off') ?></span>

          <span class="icon light:hidden" aria-hidden="true"><?= icon('moon-stars.svg') ?></span>
          <span class="font-500 light:hidden"><?= t('theme.switch.on') ?></span>
        </button>
      </div>
    </div>
  </div>

  <div class="px-lg py-3xl md:px-gutter">
    <div class="max-w-screen-lg">
      <div class="subtext flex flex-col gap-1 md:flex-row md:gap-lg md:justify-between">
        <p class="md:mr-auto">
          &copy; <?= date('Y') ?> <?= $site->title() ?>
        </p>

        <?php foreach ($site->footerPages()->toPages() as $p): ?>
          <a href="<?= $p->url() ?>" style="--un-decoration-color: var(--un-color-primary-accent)">
            <?= $p->title() ?>
          </a>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</footer>
