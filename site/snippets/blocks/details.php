<?php /** @var \Kirby\Cms\Block $block */ ?>
<details class="prose">
  <summary><?= $block->summary()->escape() ?></summary>
  <?= $block->text() ?>
</details>
