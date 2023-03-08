<?php /** @var \Kirby\Cms\Block $block */ ?>
<details>
  <summary class="prose"><?= $block->summary()->escape() ?></summary>
  <?= $block->text() ?>
</details>
