<?php /** @var \Kirby\Cms\Block $block */ ?>
<div class="border border-solid border-primary-500 text-size-[0.875rem] py-4 px-4 -mx-4 frame-primary-500 md:px-5 md:mx-0">
  <?= $block->text()->permalinksToUrls() ?>
</div>
