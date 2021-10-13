<?php
/** @var string $title */
/** @var string|null $props */
?>
<div class="container is-xl pt-xl">
  <h1 class="<?= trim('title is-intro md:text-center ' . ($props ?? ''), ' ') ?>">
    <?= $title ?>
  </h1>
  <div class="section-divider" data-animere="GrowSectionDivider"></div>
</div>
