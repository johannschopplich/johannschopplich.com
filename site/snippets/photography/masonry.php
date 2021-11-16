<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div class="grid-masonry gap-8 sm:gap-5" style="--masonry-max-width: clamp(25rem, 25vw, 30rem);">
  <?php foreach ($query as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <figure>
      <?php snippet('helpers/img', [
        'image' => $image,
        'zoomable' => true
      ]) ?>

      <?php if ($image->caption()->isNotEmpty() && !$page->isHomePage()): ?>
        <figcaption class="font-size-7 lh-heading text-contrast-medium text-center px-m py-2">
          <?= $image->caption() ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endforeach ?>
</div>
