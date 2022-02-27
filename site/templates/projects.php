<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<?php snippet('intro', ['title' => $page->text()->escape()]) ?>

<div class="content max-w-screen-lg pb-8xl">
  <div class="border-t pt-xl">
    <?php foreach ($children = $page->children()->listed() as $project): ?>
      <?php /** @var \Kirby\Cms\Page $project */ ?>
      <div class="group relative<?php e(!$project->isLast($children), ' mb-5xl') ?>"<?= attr([
        'data-animere' => !$project->isFirst($children) ? 'fadeInUpSmall' : null,
        'data-animere-duration' => '500ms'
      ], ' ') ?>>
        <p class="text-contrast-medium" style="word-spacing: 0.25ch;">
          <?= $project->subtitle()->escape() ?>
        </p>

        <h2 class="title text-2xl mb-xl">
          <a href="<?= $project->url() ?>" class="text-underline leading-tight !text-current">
            <span class="absolute inset-0" aria-hidden="true"></span>
            <?= $project->title()->escape() ?>
          </a>
        </h2>

        <?php if ($image = $project->thumbnail()->toFile()): ?>
          <figure class="aspect-ratio-4:3 -mx-lg md:mx-0 group-hover:shadow-frame">
            <?php snippet('helpers/img', [
              'image' => $image,
              'class' => 'object-cover h-full'
            ]) ?>
          </figure>
        <?php endif ?>
      </div>
    <?php endforeach ?>
  </div>
</div>
