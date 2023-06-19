<?php
/** @var \Kirby\Cms\Pages $query */
/** @var string $heading */

$year = null;
?>
<section class="content max-w-prose pb-8xl">
  <h2 class="title text-primary text-xl mb-3xl">
    <?= $heading ?>
  </h2>

  <?php foreach ($query as $post): ?>
    <?php /** @var \Kirby\Cms\Page $post */ ?>
    <?php $postY = $post->published()->toDate('Y') ?>
    <?php $isActive = $post->isActive() || $post->is($page) ?>

    <?php if ($postY !== $year): ?>
      <div class="mt-3xl mb-xs">
        <h3 class="font-heading font-600 text-size-xl text-contrast-low leading-none">
          <?= $postY ?>
        </h3>
      </div>
    <?php endif ?>

    <a <?= attr([
      'href' => $post->url(),
      'class' => 'gap-xs mb-xs flex items-start',
      'aria-current' => $isActive ? 'page' : null,
      'style' => '--du-color-link-hover: var(--du-color-primary)'
    ]) ?>>
      <span class="w-max max-w-1/2 <?php e($isActive, 'text-primary') ?>">
        <?= $post->title()->escape() ?>
      </span>
      <?php if ($post->categories()->isNotEmpty()): ?>
        <hr class="flex-grow border-contrast-medium invisible md:visible md:mt-3 du-dark:border-contrast-low">
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
