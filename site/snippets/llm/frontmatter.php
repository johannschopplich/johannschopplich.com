<?php

use Kirby\Data\Json;

/** @var \Kirby\Cms\Page $page */
/** @var array|null $fields Additional key-value pairs to merge */

$frontmatter = [
  'title' => $page->title()->value(),
  'url' => '/' . $page->uid(),
  ...($page->description()->isNotEmpty() ? ['description' => $page->description()->value()] : []),
  ...($fields ?? [])
];

?>
---
<?php foreach ($frontmatter as $key => $value): ?>
<?php if (is_array($value)): ?>
<?= $key ?>: [<?= implode(', ', $value) ?>]
<?php else: ?>
<?= $key ?>: <?= Json::encode($value) . "\n" ?>
<?php endif ?>
<?php endforeach ?>
---