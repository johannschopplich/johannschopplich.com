<?php /** @var \Kirby\Cms\Collection $query */ ?>
<div class="
  relative
  grid -mx-xl sm:grid-cols-2
  after:content-empty after:absolute after:left-0 after:right-0 after:bottom-0 after:h-1px after:bg-theme-background
">
  <?php foreach ($query as $article): ?>
    <?php /** @var \Kirby\Cms\Page $article */ ?>
    <div class="
      relative p-xl
      before:content-empty before:absolute before:left-5 before:right-5 before:bottom-0 before:h-1px before:bg-current
      after:content-empty after:absolute after:top-5 after:bottom-5 after:right-0 after:w-1px after:bg-current
    ">
      <p class="text-contrast-medium">
        <?php $format = $kirby->language()->code() === 'de' ? '%e. %B %Y' : '%e %B %Y' ?>
        <time datetime="<?= $article->published()->toDate('%Y-%m-%d') ?>">
          <?= $article->published()->toDate($format) ?>
        </time>
      </p>

      <h2 class="title text-size-lg sm:text-size-xl mb-lg">
        <a href="<?= $article->url() ?>" class="underlined hyphenate">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <?= $article->title() ?>
        </a>
      </h2>

      <p class="leading-tighter"><?= $article->description() ?></p>
  </div>
  <?php endforeach ?>
</div>
