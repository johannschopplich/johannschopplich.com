<?php

/** @var \Kirby\Cms\Pages $query */
/** @var string $heading */

$year = null;
?>
<section class="content max-w-prose pb-8xl">
  <h2 class="title text-2xl font-700 mb-3xl">
    <?= $heading ?>
  </h2>

  <?php foreach ($query as $post): ?>
    <?php /** @var \Kirby\Cms\Page $post */ ?>
    <?php $postY = $post->published()->toDate('Y') ?>
    <?php $isActive = $post->isActive() || $post->is($page) ?>

    <?php if ($postY !== $year): ?>
      <div class="mt-3xl mb-xs">
        <h3 class="title text-primary-500 un-dark:text-primary-400">
          <?= $postY ?>
        </h3>
      </div>
    <?php endif ?>

    <a <?= attr([
          'href' => $post->url(),
          'class' => 'gap-xs mb-xs flex items-start',
          'aria-current' => $isActive ? 'page' : null,
          'style' => '--un-color-link-hover: var(--un-color-primary)'
        ]) ?>>
      <span class="w-max <?php e($isActive, 'text-primary-500 un-dark:text-primary-400') ?>">
        <?= $post->title()->escape() ?>
      </span>
      <?php if ($post->categories()->isNotEmpty()): ?>
        <hr class="grow border-contrast-medium mt-3 un-dark:border-contrast-low">
        <span class="flex shrink-0 flex-wrap items-center justify-start space-x-1">
          <?php foreach ($post->categories()->split() as $category): ?>
            <span class="tag"><?= $category ?></span>
          <?php endforeach ?>
        </span>
      <?php endif ?>
    </a>

    <?php $year = $postY ?>
  <?php endforeach ?>
</section>