<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<div class="section">
  <div class="container is-md mb-xl">
    <p class="text-5 text-contrast-medium">
      <?= $page->subtitle()->escape() ?>
    </p>

    <h1 class="title text-hero text-bold lh-1">
      <?= $page->title()->escape() ?>
    </h1>

    <div class="mt-s">
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
      <div class="content mb-xxl">
        <?= $page->text()->kirbytext() ?>
      </div>
    <?php endif ?>

    <div class="text-center">
      <a href="<?= $page->parent()->url() ?>" class="button is-accent is-outlined px-s">
        <?= t('project.allWorks') ?>
      </a>
    </div>
  </div>
</div>

<?php snippet('footer') ?>
