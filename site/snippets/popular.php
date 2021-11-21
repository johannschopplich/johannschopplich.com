<?php
/** @var \Kirby\Cms\Pages $query */
/** @var string $heading */

$year = null;
?>
<div<?= attr(['class' => !$page->isHomePage() ? 'due-py-m' : null], ' ') ?>>
  <div class="due-py-xl due-container-xl-full" data-theme-inverse>
    <section class="due-container-lg">
      <h2 class="title due-text-2 sm:due-text-1 text-accent due-mb-xl"><?= $heading ?></h2>

      <?php foreach ($query as $post): ?>
        <?php /** @var \Kirby\Cms\Page $post */ ?>
        <?php $postY = $post->published()->toDate('%Y') ?>

        <?php if ($postY !== $year): ?>
          <div class="due-mt-xl due-mb-xs">
            <h3 class="due-text-5 text-contrast-low"><?= $postY ?></h3>
          </div>
        <?php endif ?>

        <a href="<?= $post->url() ?>" class="popular-article-link"<?php e($post->isActive() || $post->id() === $page->id(), ' aria-current="page"') ?>>
          <p class="due-text-5"><?= $post->title()->escape() ?></p>
          <?php if ($post->categories()->isNotEmpty()): ?>
            <hr class="flex-grow bg-transparent border-t-1 border-dashed border-contrast-lower due-mx-xs invisible md:visible">
            <p class="flex flex-wrap justify-start items-center space-x-1">
              <?php foreach ($post->categories()->split() as $category): ?>
                <span class="tag"><?= $category ?></span>
              <?php endforeach ?>
            </p>
          <?php endif ?>
        </a>

        <?php $year = $postY ?>
      <?php endforeach ?>
    </section>
  </div>
</div>
