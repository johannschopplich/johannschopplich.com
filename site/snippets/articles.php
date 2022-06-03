<?php /** @var \Kirby\Cms\Collection $query */ ?>
<?php /* FIXME: unmatched utility "-mx-xl" */ ?>
<div class="
  relative
  grid -mx-[1.25rem] sm:grid-cols-2
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
        <time datetime="<?= $article->published()->toDate('Y-MM-dd') ?>">
          <?= $article->published()->toDate(new IntlDateFormatter($kirby->languageCode(), IntlDateFormatter::LONG, IntlDateFormatter::NONE)) ?>
        </time>
      </p>

      <h2 class="title text-size-xl mb-lg">
        <a href="<?= $article->url() ?>" class="text-underline leading-tight hyphenate !text-current">
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
