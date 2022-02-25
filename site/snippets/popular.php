<?php
/** @var \Kirby\Cms\Pages $query */
/** @var string $heading */

$year = null;
?>
<section class="content max-w-prose pb-8xl">
  <h2 class="title text-xl text-accent mb-3xl"><?= $heading ?></h2>

  <?php foreach ($query as $post): ?>
    <?php /** @var \Kirby\Cms\Page $post */ ?>
    <?php $postY = $post->published()->toDate('%Y') ?>

    <?php if ($postY !== $year): ?>
      <div class="mt-3xl mb-xs">
        <h3 class="text-size-xl leading-none text-contrast-low"><?= $postY ?></h3>
      </div>
    <?php endif ?>

    <a
      href="<?= $post->url() ?>"
      class="flex items-start gap-xs mb-xs"
      <?php e($post->isActive() || $post->id() === $page->id(), 'aria-current="page"') ?>
      style="--du-color-link-hover: var(--du-color-accent);"
    >
      <span class="font-heading<?php e($post->isActive() || $post->id() === $page->id(), ' text-accent') ?>">
        <?= $post->title()->escape() ?>
      </span>
      <?php if ($post->categories()->isNotEmpty()): ?>
        <hr class="border-contrast-medium invisible md:visible md:basis-1/4 md:mt-3">
        <p class="flex-shrink-0 flex flex-wrap justify-start items-center space-x-1">
          <?php foreach ($post->categories()->split() as $category): ?>
            <span class="tag"><?= $category ?></span>
          <?php endforeach ?>
        </p>
      <?php endif ?>
    </a>

    <?php $year = $postY ?>
  <?php endforeach ?>
</section>
