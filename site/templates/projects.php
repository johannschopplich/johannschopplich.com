<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<?php snippet('intro', [
  'title' => $page->text()->kti(),
  'props' => 'due-mb-xs'
]) ?>

<section class="container-md text-center due-pb-xl">
  <?php /*
  <h2 class="title due-text-2 sm:due-text-1 text-accent text-center due-mb-2xl">
    <?= t('projects.title') ?>
  </h2>
  */ ?>

  <?php foreach ($children = $page->children()->listed() as $project): ?>
    <?php /** @var \Kirby\Cms\Page $project */ ?>
    <div class="project-item<?php e(!$project->isLast($children), ' due-mb-2xl') ?>"<?= attr([
      'data-animere' => !$project->isFirst($children) ? 'fadeInUpSmall' : null,
      'data-animere-duration' => '750ms'
    ], ' ') ?>>
      <p class="project-subtitle font-medium">
        <?= $project->subtitle()->escape() ?>
      </p>

      <h2 class="title due-text-2 sm:due-text-1 due-mb-m">
        <a href="<?= $project->url() ?>" class="underlined stretched-link">
          <?= $project->title()->escape() ?>
        </a>
      </h2>

      <?php if ($image = $project->thumbnail()->toFile()): ?>
        <figure class="project-figure aspect-ratio-4:3 -md-full-width">
          <?php snippet('helpers/img', [
            'image' => $image,
            'class' => 'object-cover'
          ]) ?>
        </figure>
      <?php endif ?>
    </div>
  <?php endforeach ?>
</section>
