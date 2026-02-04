<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
<div class="content pt-5xl md:pt-8xl">
  <div class="prose max-w-screen-lg">
    <?php foreach ($page->text()->toBlocks() as $block): ?>
      <?php /** @var \Kirby\Cms\Block $block */ ?>
      <?php if ($block->type() === 'heading' && $block->content()->level()->value() === 'h1'): ?>
        <h1 class="headline" style="--un-decoration-offset: max(2px, 0.1em);"><?= $block->text() ?></h1>
      <?php else: ?>
        <div class="max-w-prose text-contrast-medium">
          <?= $block ?>
        </div>
      <?php endif ?>
    <?php endforeach ?>
  </div>
</div>

<div class="section-divider"></div>

<div class="pb-8xl space-y-5xl md:space-y-8xl">
  <?php foreach ($children = $page->children()->listed() as $project): ?>
    <?php /** @var \Kirby\Cms\Page $project */ ?>
    <div>
      <div class="group content relative pt-xl mb-xl border-t border-t-solid border-contrast-low un-dark:border-contrast-lower">
        <h2 class="title text-2xl">
          <a
            href="<?= $project->url() ?>"
            class="link-default">
            <span class="absolute inset-0" aria-hidden="true"></span>
            <?= $project->title()->escape() ?>
          </a>
        </h2>

        <p class="subtext text-contrast-medium">
          <?= $project->subtitle()->escape() ?>
        </p>
      </div>

      <div class="mb-lg">
        <?php snippet('components/carousel', [
          'query' => $project->gallery()->toFiles(),
          'height' => $project->galleryHeight()->value()
        ]) ?>
      </div>

      <div class="content">
        <a href="<?= $project->url() ?>" class="button-callout w-full link-default">
          <span><?= t('projects.more') ?></span>
          <span class="i-tabler-arrow-right ml-1" aria-hidden="true"></span>
        </a>
      </div>
    </div>
  <?php endforeach ?>
</div>
<?php endsnippet() ?>
