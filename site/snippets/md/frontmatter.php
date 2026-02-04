<?php

use Kirby\Data\Json;

/** @var array $data Key-value pairs for frontmatter */

?>
---
<?php foreach ($data as $key => $value): ?>
<?php if (is_array($value)): ?>
<?= $key ?>: [<?= implode(', ', $value) ?>]
<?php else: ?>
<?= $key ?>: <?= Json::encode($value) . "\n" ?>
<?php endif ?>
<?php endforeach ?>
---
