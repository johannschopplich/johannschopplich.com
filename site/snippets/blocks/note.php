<?php /** @var \Kirby\Cms\Block $block */ ?>
<div class="bg-primary-500 text-white py-[1.25rem] px-[1.5rem]">
  <?= $block->text()->permalinksToUrls() ?>
</div>
