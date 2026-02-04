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
---
<?php foreach ($frontmatter as $key => $value): ?>
<?= $key ?>: <?= \Kirby\Data\Json::encode($value) . "\n" ?>
<?php endforeach ?>
---

<?php snippet('md/blocks', ['blocks' => $page->bio()->toBlocks()]) ?>

## Career

<?php foreach ($page->career()->toStructure() as $entry): ?>
### <?= $entry->company()->value() ?> — <?= $entry->role()->value() ?>

**Period:** <?= $entry->period()->value() ?>

<?php if ($entry->url()->isNotEmpty()): ?>
**Website:** <?= $entry->url()->value() ?>

<?php endif ?>
<?php if ($entry->description()->toBlocks()->isNotEmpty()): ?>
<?= snippet('md/blocks', ['blocks' => $entry->description()->toBlocks()], true) . "\n" ?>

<?php endif ?>
<?php endforeach ?>
