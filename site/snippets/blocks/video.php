<?php

/** @var \Kirby\Cms\Block $block */

// Extract the YouTube video ID from the URL
$pattern =
    '%^# Match any youtube URL
    (?:https?://)?  # Optional scheme. Either http or https
    (?:www\.)?      # Optional www subdomain
    (?:             # Group host alternatives
      youtu\.be/    # Either youtu.be,
    | youtube\.com  # or youtube.com followed by
      (?:           # Group path alternatives
        /embed/     # Either /embed/
      | /v/         # or /v/
      | /watch\?v=  # or /watch\?v=
      )             # End path alternatives.
    )               # End host alternatives.
    ([\w-]{10,12})  # Allow 10-12 for 11 char YouTube id.
    $%x';
$id = preg_match($pattern, $block->url()->value(), $matches) ? $matches[1] : null;

if (!$id) return;
?>
<figure>
  <lite-youtube videoid="<?= $id ?>" style="aspect-ratio: <?= $block->ratio()->or("16/9") ?>"></lite-youtube>
  <?php if ($block->caption()->isNotEmpty()): ?>
    <figcaption><?= $block->caption() ?></figcaption>
  <?php endif ?>
</figure>
