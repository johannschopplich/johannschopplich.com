<?php

/** @var int|null $repeat */
?>
<div class="group flex gap-[var(--gap,1rem)] py-xl overflow-hidden">
  <?php foreach (range(1, $repeat ?? 4) as $i): ?>
    <div class="animate-marquee flex flex-row shrink-0 justify-around gap-[var(--gap,1rem)] [@media(hover:hover)]:group-hover:[animation-play-state:paused]">
      <?= $slot ?>
    </div>
  <?php endforeach ?>
</div>