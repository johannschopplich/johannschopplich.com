<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
layout();
?>

<?php snippet('intro', ['title' => $page->text()]) ?>

<div class="content max-w-screen-lg pb-8xl space-y-5xl">
  <?php foreach ($children = $page->children()->listed() as $project): ?>
    <?php /** @var \Kirby\Cms\Page $project */ ?>
    <div class="group relative pt-xl border-t"<?= attr([
      'data-animere' => !$project->isFirst($children) ? 'fadeInRight' : null,
      'data-animere-duration' => '500ms'
    ], ' ') ?>>
      <p class="text-contrast-medium word-spacing-[0.5ch]">
        <?= $project->subtitle()->escape() ?>
      </p>

      <h2 class="title text-2xl leading-heading mb-xl">
        <a href="<?= $project->url() ?>" class="text-underline leading-tight !text-current">
          <span class="absolute inset-0" aria-hidden="true"></span>
          <?= $project->title()->escape() ?>
        </a>
      </h2>

      <?php if ($image = $project->thumbnail()->toFile()): ?>
        <figure class="content-breakout aspect-ratio-4/3 md:group-hover:shadow-frame">
          <?php snippet('helpers/img', [
            'file' => $image,
            'class' => 'object-cover h-full'
          ]) ?>
        </figure>
      <?php endif ?>
    </div>
  <?php endforeach ?>
</div>
