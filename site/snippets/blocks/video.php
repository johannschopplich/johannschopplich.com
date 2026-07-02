<?php

use Kirby\Http\Url;

/** @var \Kirby\Cms\Block $block */

// Extract the YouTube video ID from the URL
$videoIdPattern =
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

$id = preg_match($videoIdPattern, $block->url()->value(), $matches) ? $matches[1] : null;
if (!$id) return;

/** @var \Kirby\Cms\File */
$image = $block->thumbnail()->toFile();
$bgImage = $image?->thumb([
  'width' => 1280,
  'quality' => 70
])->url();

// Feed readers don't know the <lite-youtube> element – link a thumbnail instead
$isFeed = preg_match('/feeds\/(?:rss|json)$/', Url::current());

?>
<figure class="is-outset">
  <?php if ($isFeed): ?>
    <a href="https://www.youtube.com/watch?v=<?= $id ?>">
      <img <?= attr([
        'src' => $bgImage ?? "https://img.youtube.com/vi/{$id}/hqdefault.jpg",
        'alt' => $block->title()->or(t('video.play'))->value()
      ]) ?>>
    </a>
  <?php else: ?>
    <lite-youtube <?= attr([
      'videoid' => $id,
      'title' => $block->title()->value(),
      'playlabel' => $block->title()->or(t('video.play'))->value(),
      'style' => implode(';', array_filter([
        $bgImage ? "background-image: url({$bgImage})" : null,
        'aspect-ratio: ' . $block->ratio()->or('16/9')->value()
      ]))
    ]) ?>></lite-youtube>
  <?php endif ?>
  <?php if ($block->caption()->isNotEmpty()): ?>
    <figcaption><?= $block->caption()->permalinksToUrls() ?></figcaption>
  <?php endif ?>
</figure>
