<?php
/** @var string $title */
/** @var string|null $className */
?>
<div class="content pt-8xl max-w-screen-lg">
  <h1 class="<?= trim('title font-900 text-3xl md:text-size-[calc(var(--du-text-4xl)+1vw)] ' . ($className ?? ''), ' ') ?>">
    <?= $title ?>
  </h1>
  <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>
</div>
