<?php
/** @var string|null $className */
/** @var string|null $draggable */
?>
<div class="content pt-8xl max-w-screen-lg">
  <h1 <?= attr([
    'class' => trim('title font-900 text-2xl md:text-size-[calc(var(--du-text-4xl)+1vw)] ' . ($className ?? ''), ' '),
    'data-draggable' => $draggable ?? null,
  ]) ?>>
    <?= $slot ?>
  </h1>
  <div class="section-divider my-lg" data-animere="GrowSectionDivider"></div>
</div>
