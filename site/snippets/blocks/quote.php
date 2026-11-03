<?php

/** @var \Kirby\Cms\Block $block */

?>
<blockquote>
  <?= $block->text()->permalinksToUrls() ?>
  <?php if ($block->citation()->isNotEmpty()): ?>
    <footer>
      <?= $block->citation()->permalinksToUrls() ?>
    </footer>
  <?php endif ?>
</blockquote>
