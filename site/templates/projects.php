<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
?>

<?php snippet('layouts/default', slots: true) ?>
  <?php snippet('intro', slots: true) ?>
    <?= $page->text() ?>
  <?php endsnippet() ?>

  <div class="pb-8xl space-y-5xl md:space-y-8xl">
    <?php foreach ($children = $page->children()->listed() as $project): ?>
      <?php /** @var \Kirby\Cms\Page $project */ ?>
      <div>
        <div class="content group pt-xl mb-xl relative border-t border-t-solid du-dark:border-contrast-lower">
          <p class="text-contrast-medium word-spacing-[0.5ch]">
            <?= $project->subtitle()->escape() ?>
          </p>

          <h2 class="title text-2xl">
            <a href="<?= $project->url() ?>" class="text-underline leading-tight !text-current">
              <span class="absolute inset-0" aria-hidden="true"></span>
              <?= $project->title()->escape() ?>
            </a>
          </h2>
        </div>

        <div class="mb-lg">
          <?php snippet('shortcuts/slider', [
            'query' => $project->gallery()->toFiles(),
            'height' => $project->galleryHeight()->value(),
            'link' => $project->url(),
            'animation' => 'fadeInLeft'
          ]) ?>
        </div>

        <div class="content">
          <a href="<?= $project->url() ?>" class="action-button">
            <?= t('projects.more') ?><span class="i-bx-right-arrow-alt ml-1" aria-hidden="true"></span>
          </a>
        </div>
      </div>
    <?php endforeach ?>
  </div>
<?php endsnippet() ?>
