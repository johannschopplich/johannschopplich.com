<?php

/** @var \Kirby\Cms\Pages $query */

?>
<div class="
  relative grid -mx-xl
  after:content-empty after:absolute after:inset-x-0 after:bottom-0 after:h-[1px] after:bg-theme-background
  sm:grid-cols-2
">
  <?php foreach ($query as $article): ?>
    <div class="
      relative p-xl
      before:content-empty before:absolute before:inset-x-5 before:bottom-0 before:border-b before:border-solid before:border-contrast-low dark:before:border-contrast-lower
      after:content-empty after:absolute after:inset-y-5 after:right-0 after:border-r after:border-solid after:border-contrast-low dark:after:border-contrast-lower
    ">
      <p class="caption mb-[1px] text-contrast-soft">
        <time datetime="<?= $article->published()->toDate('Y-MM-dd') ?>">
          <?= $article->published()->toDate(dateFormatter()) ?>
        </time>
      </p>

      <h2 class="title mb-xs text-size-xl">
        <a
          href="<?= $article->url() ?>"
          class="link-default hyphenate">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <?= $article->title() ?>
        </a>
      </h2>

      <div class="prose text-sm text-contrast-medium">
        <p><?= $article->description() ?></p>
      </div>
    </div>
  <?php endforeach ?>
</div>
