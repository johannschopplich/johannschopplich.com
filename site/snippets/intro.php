<?php
/** @var string $title */
/** @var string|null $props */
?>
<div class="container-xl pt-13">
  <h1 class="<?= trim('title is-intro md:text-center ' . ($props ?? ''), ' ') ?>">
    <?= $title ?>
  </h1>
  <div class="section-divider" data-animere="GrowSectionDivider"></div>
</div>
