<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$gallery = $page->gallery()->toFiles();

$frontmatter = [
  'title' => $page->title()->value(),
  'url' => '/' . $page->uid(),
  ...($page->subtitle()->isNotEmpty() ? ['subtitle' => $page->subtitle()->value()] : []),
  ...($page->description()->isNotEmpty() ? ['description' => $page->description()->value()] : []),
  // Include gallery image URLs for AI context
  ...($gallery->isNotEmpty() ? ['gallery' => $gallery->pluck('url')] : [])
];

?>
---
<?php foreach ($frontmatter as $key => $value): ?>
<?php if (is_array($value)): ?>
<?= $key ?>:
<?php foreach ($value as $item): ?>
  - <?= $item . "\n" ?>
<?php endforeach ?>
<?php else: ?>
<?= $key ?>: <?= \Kirby\Data\Json::encode($value) . "\n" ?>
<?php endif ?>
<?php endforeach ?>
---

# <?= $page->title()->value() . "\n" ?>

<?php if ($page->subtitle()->isNotEmpty()): ?>
*<?= $page->subtitle()->value() ?>*

<?php endif ?>
<?php snippet('md/blocks', ['blocks' => $page->text()->toBlocks()]) ?>
