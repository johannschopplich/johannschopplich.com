<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$frontmatter = [
  'title' => $page->title()->value(),
  'url' => '/' . $page->uid(),
  ...($page->description()->isNotEmpty() ? ['description' => $page->description()->value()] : [])
];

?>
<?php snippet('md/frontmatter', ['data' => $frontmatter]) ?>

# <?= $page->title()->value() ?>

<?php snippet('md/blocks', ['blocks' => $page->text()->toBlocks()]) ?>

<?php foreach ($page->children()->listed() as $project): ?>

## <?= $project->title()->value() ?>

- **URL:** <?= $project->url() ?>

<?php if ($project->subtitle()->isNotEmpty()): ?>
*<?= $project->subtitle()->value() ?>*

<?php endif ?>
<?php if ($project->description()->isNotEmpty()): ?>
<?= $project->description()->value() ?>

<?php endif ?>
<?php endforeach ?>
