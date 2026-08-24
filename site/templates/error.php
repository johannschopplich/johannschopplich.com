<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var string|null $lang */
/** @var \Kirby\Cms\Blocks $blocks */

?>
<?php snippet('layouts/default', slots: true) ?>

<div class="pt-5xl pb-8xl md:pt-8xl">

  <div class="border-b border-solid border-contrast-low dark:border-contrast-lower">
    <div class="px-lg pb-5xl md:px-gutter">
      <div class="max-w-screen-lg">
        <!-- Reserves the canvas box before the deferred module upgrades the
             element. The ratio is the raster grid – re-measure it if the text
             or the heading face changes. -->
        <pixel-text text="404" class="block aspect-[40/18] font-heading">
          <noscript>
            <!-- Unlayered, so it outranks the reservation utility in `@layer default`. -->
            <style>pixel-text { aspect-ratio: auto }</style>
            <span class="headline">404</span>
          </noscript>
        </pixel-text>

        <!-- The wrapper carries `hidden` because `action-link` would outrank it. -->
        <div class="mt-lg" data-pixel-restore hidden>
          <button type="button" class="action-link link-default">
            <span class="i-tabler-refresh mr-1" aria-hidden="true"></span>
            <span><?= t('error.restore') ?></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="px-lg pt-5xl md:px-gutter">
    <div class="max-w-screen-lg">
      <h1 class="headline">
        <?= $page->customTitle()->or($page->title())->escape() ?>
      </h1>
    </div>
  </div>

  <div class="pt-5xl">
    <?php snippet('components/prose-blocks', compact('blocks', 'lang')) ?>
  </div>

</div>

<?php endsnippet() ?>
