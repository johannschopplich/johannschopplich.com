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
