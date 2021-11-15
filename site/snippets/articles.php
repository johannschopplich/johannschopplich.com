<?php /** @var \Kirby\Cms\Collection $query */ ?>
<div class="grid sm:grid-cols-2 gap-13">
  <?php foreach ($query as $article): ?>
    <?php /** @var \Kirby\Cms\Page $article */ ?>
    <div class="relative">
      <p class="text-contrast-low font-size-7 font-medium text-uppercase">
        <?php $format = $kirby->language()->code() === 'de' ? '%e. %B %Y' : '%e %B %Y' ?>
        <time datetime="<?= $article->published()->toDate('%Y-%m-%d') ?>">
          <?= $article->published()->toDate($format) ?>
        </time>
      </p>

      <h2 class="title font-size-4 sm:font-size-3 mb-3">
        <a href="<?= $article->url() ?>" class="stretched-link underlined hyphenated">
          <?= $article->title() ?>
        </a>
      </h2>

      <p><?= $article->description() ?></p>
  </div>
  <?php endforeach ?>
</div>
