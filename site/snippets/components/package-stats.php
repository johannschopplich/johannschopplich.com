<?php

/** @var array $packages */

// Maps a weekly download series onto a 0–100 viewBox, normalized to the
// series' own peak so every curve fills its tile regardless of scale. The
// factor 90 leaves headroom above the peak.
$sparklinePoints = function (array $weeklyDownloads): string {
  $peak = max($weeklyDownloads) ?: 1;
  $lastIndex = max(count($weeklyDownloads) - 1, 1);
  $points = [];

  foreach ($weeklyDownloads as $index => $downloads) {
    $points[] = round($index / $lastIndex * 100, 2) . ',' . round(100 - $downloads / $peak * 90, 2);
  }

  return implode(' ', $points);
};

?>
<div class="
  relative grid -mx-xl
  after:content-empty after:absolute after:inset-x-0 after:bottom-0 after:h-[1px] after:bg-theme-background
  sm:grid-cols-3
">
  <?php foreach ($packages as $package): ?>
    <?php $sparkline = $sparklinePoints($package['weeklyDownloads']) ?>
    <div class="
      relative p-xl
      before:content-empty before:absolute before:inset-x-5 before:bottom-0 before:border-b before:border-solid before:border-contrast-low dark:before:border-contrast-lower
      after:content-empty after:absolute after:inset-y-5 after:right-0 after:border-r after:border-solid after:border-contrast-low dark:after:border-contrast-lower
    ">
      <p class="caption mb-[1px] text-contrast-soft">
        <?= t('packages.downloads') ?>
      </p>

      <div class="relative mb-lg">
        <svg class="absolute inset-0 w-full h-full text-primary-accent" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
          <polygon points="0,100 <?= $sparkline ?> 100,100" fill="currentColor" opacity="0.1" />
          <polyline points="<?= $sparkline ?>" fill="none" stroke="currentColor" stroke-width="1.5" vector-effect="non-scaling-stroke" opacity="0.25" />
        </svg>
        <p class="title relative text-4xl tabular-nums md:text-5xl"><?= formatCount($package['monthlyDownloads']) ?></p>
      </div>

      <h3 class="subtext">
        <a
          href="<?= esc($package['url']) ?>"
          target="_blank"
          class="link-default">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <?= esc($package['name']) ?>
        </a>
      </h3>

      <div class="prose text-sm text-contrast-medium">
        <p><?= esc($package['description']) ?></p>
      </div>
    </div>
  <?php endforeach ?>
</div>
