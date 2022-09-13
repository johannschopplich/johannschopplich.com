<?php
/** @var string $title */
/** @var string|null $props */
?>
<div class="content pt-8xl max-w-screen-lg">
  <h1 class="<?= trim('title font-900 text-3xl md:text-4xl ' . ($props ?? ''), ' ') ?>">
    <?= $title ?>
  </h1>
  <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>
</div>
