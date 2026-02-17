<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="pt-5xl pb-8xl md:pt-8xl">
  <div class="px-lg md:px-gutter">
    <div class="prose max-w-prose">
      <h1 class="sr-only">
        <?= $page->title()->escape() ?>
      </h1>
    </div>
  </div>

  <?php snippet('components/prose-blocks', [
      'blocks' => $page->text()->toBlocks(),
      'breakoutClass' => 'mb-8xl'
  ]) ?>
</div>
<?php endsnippet() ?>
