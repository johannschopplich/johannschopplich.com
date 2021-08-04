<?php
/** @var \Kirby\Cms\Pages $query */
/** @var string $heading */

$year = null;
?>
<div<?= attr(['class' => !$page->isHomePage() ? 'py-m' : null], ' ') ?>>
  <div class="section container is-xl is-fullwidth" data-theme-inverse>
    <section class="container is-lg">
      <h2 class="title text-2 sm:text-1 text-accent mb-xl"><?= $heading ?></h2>

      <?php foreach ($query as $post): ?>
        <?php /** @var \Kirby\Cms\Page $post */ ?>
        <?php $postY = $post->published()->toDate('%Y') ?>

        <?php if ($postY !== $year): ?>
          <div class="mt-l mb-xs">
            <h3 class="text-5 text-contrast-low"><?= $postY ?></h3>
          </div>
        <?php endif ?>

        <a href="<?= $post->url() ?>" class="popular-article-link"<?php e($post->isActive() || $post->id() === $page->id(), ' aria-current="page"') ?>>
          <p class="popular-article-title"><?= $post->title()->escape() ?></p>
          <?php if ($post->categories()->isNotEmpty()): ?>
            <hr class="popular-article-spacer">
            <p class="popular-article-categories">
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
