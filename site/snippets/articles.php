<?php

/** @var \Kirby\Cms\Collection $query */ ?>
<div class="
  relative grid -mx-xl sm:grid-cols-2
  after:content-empty after:absolute after:inset-x-0 after:bottom-0 after:h-1px after:bg-theme-background
">
  <?php foreach ($query as $article): ?>
    <?php /** @var \Kirby\Cms\Page $article */ ?>
    <div class="
      relative p-xl
      before:content-empty before:absolute before:inset-x-5 before:bottom-0 before:h-1px before:bg-contrast-low un-dark:before:bg-contrast-lower
      after:content-empty after:absolute after:inset-y-5 after:right-0 after:w-1px after:bg-contrast-low un-dark:after:bg-contrast-lower
    ">
      <p class="text-contrast-medium text-sm font-500 uppercase">
        <time datetime="<?= $article->published()->toDate('Y-MM-dd') ?>">
          <?= $article->published()->toDate(dateFormatter()) ?>
        </time>
      </p>

      <h2 class="title text-size-xl font-700 mb-lg">
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