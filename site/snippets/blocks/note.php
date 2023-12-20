<?php /** @var \Kirby\Cms\Block $block */ ?>
<div class="border border-solid border-primary-500 text-size-[0.875rem] py-[1rem] px-[1.25rem] shadow-frame">
  <?= $block->text()->permalinksToUrls() ?>
</div>
