<?php /** @var \Kirby\Cms\Collection $query */ ?>
<div class="articles-grid">
  <?php foreach ($query as $article): ?>
    <?php /** @var \Kirby\Cms\Page $article */ ?>
    <div class="position-relative">
      <p class="text-contrast-low text-7 text-medium text-uppercase">
        <?php $format = $kirby->language()->code() === 'de' ? '%e. %B %Y' : '%e %B %Y' ?>
        <time datetime="<?= $article->published()->toDate('%Y-%m-%d') ?>">
          <?= $article->published()->toDate($format) ?>
        </time>
      </p>

      <h2 class="title text-4 sm:text-3 mb-s">
        <a href="<?= $article->url() ?>" class="stretched-link text-underline hyphenated">
          <?= $article->title() ?>
        </a>
      </h2>

      <p><?= $article->description() ?></p>
  </div>
  <?php endforeach ?>
</div>
