<?php

/** @var \Kirby\Cms\Block $block */ ?>
<div class="note">
  <?= $block->text()->permalinksToUrls() ?>
</div>