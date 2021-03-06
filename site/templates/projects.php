<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>
<?php snippet('header') ?>

<?php snippet('intro', ['title' => $page->text()->kti()]) ?>

<section class="container is-md text-center pb-xl">
  <h2 class="title text-3 sm:text-1 text-accent text-center mb-xxl">
    <?= t('projects.title') ?>
  </h2>

  <?php foreach ($children = $page->children()->listed() as $project): ?>
    <?php /** @var \Kirby\Cms\Page $project */ ?>
    <div class="project-item<?php e(!$project->isLast($children), ' mb-xxl') ?>" data-animere="fadeInUpSmall">
      <p class="project-subtitle text-medium">
        <?= $project->subtitle() ?>
      </p>

      <h2 class="title text-2 sm:text-1 mb-m">
        <a href="<?= $project->url() ?>" class="text-underline stretched-link">
          <?= $project->title() ?>
        </a>
      </h2>

      <?php if ($image = $project->thumbnail()->toFile()): ?>
        <figure class="project-figure stretched-fullwidth@md aspect-ratio-fixed is-4:3">
          <?php snippet('image', [
            'image' => $image,
            'class' => 'covered'
          ]) ?>
        </figure>
      <?php endif ?>
    </div>
  <?php endforeach ?>
</section>

<?php snippet('footer') ?>
