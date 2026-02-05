<?php

use Kirby\Toolkit\I18n;

$complementaryLanguage = $kirby
  ->languages()
  ->filter(fn ($language) => $language->code() !== $kirby->language()->code())
  ->first();
?>
<footer class="sticky top-100vh">
  <div class="content pt-8xl pb-3xl bg-contrast-lowest border-t border-contrast-low">
    <div class="max-w-screen-lg">
      <div class="mb-8xl">
        <h3 class="title text-2xl mb-3xl">
          <?= t('generic.contact') ?>
        </h3>

        <ul>
          <?php foreach ($site->footerLinks()->toStructure() as $item): ?>
            <li class="group border-b border-contrast-low hover:border-current">
              <a href="<?= $item->url() ?>" class="grid grid-cols-4 items-center py-sm sm:grid-cols-6 lg:grid-cols-9">
                <span class="col-start-1 col-end-4 font-bold sm:col-start-1 sm:col-end-3 lg:col-start-1 lg:col-end-4"><?= $item->category()->escape() ?></span>
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
          class="inline-flex items-center gap-2 text-sm font-500 link-default">
          <span class="icon" aria-hidden="true">
            <?= icon('earth.svg') ?>
          </span>
          <span>
            <?= I18n::template('languages.switch', null, ['language' => t('languages.' . $complementaryLanguage->code())]) ?>
          </span>
        </a>
        <button
          class="inline-flex items-center gap-2 text-sm link-default"
          data-theme-switcher>
          <span class="icon un-dark:hidden" aria-hidden="true"><?= icon('sun.svg') ?></span>
          <span class="font-500 un-dark:hidden"><?= t('theme.switch.off') ?></span>

          <span class="icon un-light:hidden" aria-hidden="true"><?= icon('moon-stars.svg') ?></span>
          <span class="font-500 un-light:hidden"><?= t('theme.switch.on') ?></span>
        </button>
      </div>
    </div>
  </div>

  <div class="content py-3xl bg-contrast-lower border-t border-contrast-low">
    <div class="max-w-screen-lg">
      <div class="flex flex-col gap-1 text-sm font-500 md:flex-row md:gap-lg md:justify-between">
        <p class="md:mr-auto">
          &copy; <?= date('Y') ?> <?= $site->title() ?>
        </p>

        <?php foreach ($site->footerPages()->toPages() as $p): ?>
          <a href="<?= $p->url() ?>" style="--un-decoration-color: var(--un-color-primary)">
            <?= $p->title() ?>
          </a>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</footer>
