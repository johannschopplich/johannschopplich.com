<?php

/** @var int|null $repeat */
?>
<div class="flex gap-[var(--gap,1rem)] overflow-hidden">
  <?php foreach (range(1, $repeat ?? 4) as $i): ?>
    <div class="marquee flex flex-row shrink-0 justify-around gap-[var(--gap,1rem)]">
      <?= $slot ?>
    </div>
  <?php endforeach ?>
</div>