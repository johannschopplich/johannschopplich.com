<?php
/** @var string $title */
/** @var string|null $props */
?>
<div class="due-container-xl due-pt-xl">
  <h1 class="<?= trim('due-title is-intro md:text-center ' . ($props ?? ''), ' ') ?>">
    <?= $title ?>
  </h1>
  <div class="section-divider" data-animere="GrowSectionDivider"></div>
</div>
