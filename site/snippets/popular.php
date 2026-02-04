<?php

/** @var \Kirby\Cms\Pages $query */
/** @var string $heading */

$year = null;
?>
<section class="content pb-8xl">
  <div class="max-w-prose">
    <h2 class="title text-2xl mb-3xl">
      <?= $heading ?>
    </h2>

    <?php foreach ($query as $post): ?>
      <?php /** @var \Kirby\Cms\Page $post */ ?>
      <?php $postY = $post->published()->toDate('Y') ?>
      <?php $isActive = $post->isActive() || $post->is($page) ?>

      <?php if ($postY !== $year): ?>
        <div class="mt-3xl mb-xs">
          <h3 class="title">
            <?= $postY ?>
          </h3>
        </div>
      <?php endif ?>

      <a <?= attr([
            'href' => $post->url(),
            'class' => 'gap-xs mb-xs flex items-start ' . (!$isActive ? ' link-default' : ''),
            'aria-current' => $isActive ? 'page' : null
          ]) ?>>
        <span class="w-max <?php e($isActive, 'underline-default') ?>">
          <?= $post->title()->escape() ?>
        </span>
        <?php if ($post->categories()->isNotEmpty()): ?>
          <hr class="grow self-center border-contrast-medium un-dark:border-contrast-low">
          <span class="flex shrink-0 flex-wrap gap-1">
            <?php foreach ($post->categories()->split() as $category): ?>
              <span class="tag"><?= $category ?></span>
            <?php endforeach ?>
          </span>
        <?php endif ?>
      </a>

      <?php $year = $postY ?>
    <?php endforeach ?>
  </div>
</section>