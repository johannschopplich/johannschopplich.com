<?php

/** @var \Kirby\Cms\Collection $query */ ?>
<div class="
  relative grid -mx-xl sm:grid-cols-2
  after:content-empty after:absolute after:inset-x-0 after:bottom-0 after:h-[1px] after:bg-theme-background
">
  <?php foreach ($query as $article): ?>
    <?php /** @var \Kirby\Cms\Page $article */ ?>
    <div class="
      relative p-xl
      before:content-empty before:absolute before:inset-x-5 before:bottom-0 before:border-b before:border-dotted before:border-contrast-low un-dark:before:border-contrast-lower
      after:content-empty after:absolute after:inset-y-5 after:right-0 after:border-r after:border-dotted after:border-contrast-low un-dark:after:border-contrast-lower
    ">
      <p class="text-xs font-600 tracking-[0.125ch] uppercase text-contrast-soft mb-[1px]">
        <time datetime="<?= $article->published()->toDate('Y-MM-dd') ?>">
          <?= $article->published()->toDate(dateFormatter()) ?>
        </time>
      </p>

      <h2 class="title text-size-xl mb-xs">
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
