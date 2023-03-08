<?php /** @var \Kirby\Cms\Block $block */ ?>
<details>
  <summary><?= $block->summary()->escape() ?></summary>
  <?= $block->text() ?>
</details>
