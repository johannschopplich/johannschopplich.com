<?php
/** @var string $title */
/** @var string|null $props */
?>
<div class="content max-w-screen-lg pt-8xl">
  <h1 class="<?= trim('title text-3xl md:text-4xl font-900 ' . ($props ?? ''), ' ') ?>">
    <?= $title ?>
  </h1>
  <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>
</div>
