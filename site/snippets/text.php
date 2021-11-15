<?php /** @var \Kirby\Cms\Page $page */ ?>
<div class="section">
  <div class="container max-w-[60ch] mb-13">
    <h1 class="title font-size-hero font-bold lh-none">
      <?= $page->title()->escape() ?>
    </h1>

    <?php if ($page->published()->exists() && $page->published()->isNotEmpty()): ?>
      <p class="text-contrast-medium">
        <?php $format = $kirby->languageCode() === 'de' ? '%e. %B %Y' : '%e %B %Y' ?>
        <?= t('article.publishedAt') ?>
        <time datetime="<?= $page->published()->toDate('%Y-%m-%d') ?>">
          <?= $page->published()->toDate($format) ?>
        </time>
      </p>
    <?php endif ?>

    <div class="mt-3">
      <?= asset('assets/img/article-spacer.svg')->read() ?>
    </div>
  </div>

  <div class="container max-w-[60ch]">
    <section class="content"<?= attr(['lang' => $textLanguageCode], ' ') ?>>
      <?= $text ?>

      <?php if (
        $page->parent() !== null &&
        $page->parent()->template()->name() === 'articles'
      ): ?>
        <?= $page->parent()->articleFooter()->kt() ?>
      <?php endif ?>
    </section>
  </div>
</div>
