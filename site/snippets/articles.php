<?php /** @var \Kirby\Cms\Collection $query */ ?>
<div class="
  -mx-xl relative grid sm:grid-cols-2
  after:content-empty after:h-1px after:bg-theme-background after:absolute after:inset-x-0 after:bottom-0
">
  <?php foreach ($query as $article): ?>
    <?php /** @var \Kirby\Cms\Page $article */ ?>
    <div class="
      p-xl relative
      before:content-empty before:h-1px before:absolute before:inset-x-5 before:bottom-0 before:bg-current
      after:content-empty after:w-1px after:absolute after:inset-y-5 after:right-0 after:bg-current
    ">
      <p class="text-contrast-medium">
        <time datetime="<?= $article->published()->toDate('Y-MM-dd') ?>">
          <?= $article->published()->toDate(dateFormatter()) ?>
        </time>
      </p>

      <h2 class="title text-size-xl mb-lg">
        <a href="<?= $article->url() ?>" class="text-underline hyphenate leading-tight !text-current">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <?= $article->title() ?>
        </a>
      </h2>

      <p>
        <?= $article->description() ?>
      </p>
  </div>
  <?php endforeach ?>
</div>
