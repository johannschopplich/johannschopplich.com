<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<div class="section">
  <div class="container is-md mb-13">
    <p class="font-size-5 text-contrast-medium">
      <?= $page->subtitle()->escape() ?>
    </p>

    <h1 class="title text-hero font-bold lh-none">
      <?= $page->title()->escape() ?>
    </h1>

    <div class="mt-3">
      <?= asset('assets/img/article-spacer.svg')->read() ?>
    </div>
  </div>

  <div class="container is-lg">
    <div class="v-stacked">
      <?php foreach ($files = $page->gallery()->toFiles() as $image): ?>
        <figure>
          <?php snippet('image', [
            'image' => $image,
            'zoomable' => true
          ]) ?>
        </figure>
      <?php endforeach ?>
    </div>
  </div>
</div>

<div class="section">
  <div class="container is-sm">
    <?php if ($page->text()->isNotEmpty()): ?>
      <div class="content mb-21">
        <?= $page->text()->kirbytext() ?>
      </div>
    <?php endif ?>

    <div class="text-center">
      <a href="<?= $page->parent()->url() ?>" class="button is-accent is-outlined px-3">
        <?= t('project.allWorks') ?>
      </a>
    </div>
  </div>
</div>

<?php snippet('footer') ?>
