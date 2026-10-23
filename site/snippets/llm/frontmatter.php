<?php

use Kirby\Data\Json;

/** @var \Kirby\Cms\Page $page */
/** @var array|null $fields Additional key-value pairs to merge */

$frontmatter = [
  'title' => $page->title()->value(),
  'url' => $page->url(),
  ...($page->description()->isNotEmpty() ? ['description' => $page->description()->value()] : []),
  ...($fields ?? [])
];

?>
---
<?php foreach ($frontmatter as $key => $value): ?>
<?= $key ?>: <?= Json::encode($value) . "\n" ?>
<?php endforeach ?>
---