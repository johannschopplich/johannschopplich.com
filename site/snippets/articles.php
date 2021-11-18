<?php /** @var \Kirby\Cms\Collection $query */ ?>
<div class="grid sm:grid-cols-2 gap-13">
  <?php foreach ($query as $article): ?>
    <?php /** @var \Kirby\Cms\Page $article */ ?>
    <div class="relative">
      <p class="text-contrast-low due-text-7 font-medium text-uppercase">
        <?php $format = $kirby->language()->code() === 'de' ? '%e. %B %Y' : '%e %B %Y' ?>
        <time datetime="<?= $article->published()->toDate('%Y-%m-%d') ?>">
          <?= $article->published()->toDate($format) ?>
        </time>
      </p>

      <h2 class="title due-text-4 sm:due-text-3 due-mb-s">
        <a href="<?= $article->url() ?>" class="stretched-link underlined hyphenated">
          <?= $article->title() ?>
        </a>
      </h2>

      <p><?= $article->description() ?></p>
  </div>
  <?php endforeach ?>
</div>
