<?php
/** @var \Kirby\Cms\Pages $query */
/** @var string $heading */

$year = null;
?>
<section class="content pb-8xl max-w-prose">
  <h2 class="title text-primary mb-3xl text-xl">
    <?= $heading ?>
  </h2>

  <?php foreach ($query as $post): ?>
    <?php /** @var \Kirby\Cms\Page $post */ ?>
    <?php $postY = $post->published()->toDate('Y') ?>
    <?php $isActive = $post->isActive() || $post->id() === $page->id() ?>

    <?php if ($postY !== $year): ?>
      <div class="mt-3xl mb-xs">
        <h3 class="text-size-xl text-contrast-low leading-none"><?= $postY ?></h3>
      </div>
    <?php endif ?>

    <a
      href="<?= $post->url() ?>"
      class="gap-xs mb-xs flex items-start"
      <?php e($isActive, 'aria-current="page"') ?>
      style="--du-color-link-hover: var(--du-color-primary)"
    >
      <span class="flex-grow font-heading font-500 <?php e($isActive, 'text-primary') ?>">
        <?= $post->title()->escape() ?>
      </span>
      <?php if ($post->categories()->isNotEmpty()): ?>
        <hr class="border-contrast-medium invisible md:visible md:mt-3 md:basis-1/4">
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
