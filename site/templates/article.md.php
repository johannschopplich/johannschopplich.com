<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$frontmatter = [
  'title' => $page->title()->value(),
  'url' => '/' . $page->uid(),
  'date' => $page->published()->toDate('Y-m-d'),
  'author' => $site->title()->value(),
  ...($page->categories()->isNotEmpty() ? ['categories' => array_map('trim', explode(',', $page->categories()->value()))] : []),
  ...($page->description()->isNotEmpty() ? ['description' => $page->description()->value()] : [])
];

?>
---
<?php foreach ($frontmatter as $key => $value): ?>
<?php if (is_array($value)): ?>
<?= $key ?>: [<?= implode(', ', $value) ?>]
<?php else: ?>
<?= $key ?>: <?= \Kirby\Data\Json::encode($value) . "\n" ?>
<?php endif ?>
<?php endforeach ?>
---

# <?= $page->title()->value() . "\n" ?>

<?php snippet('md/blocks', ['blocks' => $page->text()->toBlocks()]) ?>
