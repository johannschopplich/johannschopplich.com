<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
  <div class="content pt-8xl max-w-screen-lg">
    <div class="max-w-prose prose">
      <?php foreach ($page->text()->toBlocks() as $block): ?>
        <?php /** @var \Kirby\Cms\Block $block */ ?>
        <?php if ($block->type() === 'heading' && $block->content()->level() === 'h1'): ?>
          <h1 class="title font-900 text-size-2xl leading-heading md:text-size-4xl"><?= $block->text() ?></h1>
        <?php else: ?>
          <?= $block ?>
        <?php endif ?>
      <?php endforeach ?>
    </div>

    <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>
  </div>

  <div class="pb-8xl space-y-5xl md:space-y-8xl">
    <?php foreach ($children = $page->children()->listed() as $project): ?>
      <?php /** @var \Kirby\Cms\Page $project */ ?>
      <div>
        <div class="content group pt-xl mb-xl relative border-t border-t-solid du-dark:border-contrast-lower">
          <p class="text-contrast-medium font-200">
            <?= $project->subtitle()->escape() ?>
          </p>

          <h2 class="title text-2xl">
            <a href="<?= $project->url() ?>" class="text-underline leading-tight !text-current">
              <span class="absolute inset-0" aria-hidden="true"></span>
              <?= $project->title()->escape() ?>
            </a>
          </h2>
        </div>

        <div class="mb-lg">
          <?php snippet('slider', [
            'query' => $project->gallery()->toFiles(),
            'height' => $project->galleryHeight()->value(),
            'links' => false
          ]) ?>
        </div>

        <div class="content">
          <a href="<?= $project->url() ?>" class="action-button">
            <?= t('projects.more') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
          </a>
        </div>
      </div>
    <?php endforeach ?>
  </div>
<?php endsnippet() ?>
