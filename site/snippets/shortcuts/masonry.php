<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div class="masonry-grid gap-5xl sm:gap-lg" style="--masonry-col-max-w: clamp(25rem, 25vw, 30rem);">
  <?php foreach ($query as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <figure>
      <?php snippet('shortcuts/img', [
        'file' => $image,
        'zoomable' => true
      ]) ?>

      <?php if ($image->caption()->isNotEmpty() && !$page->isHomePage()): ?>
        <figcaption class="content text-contrast-medium text-size-xs font-heading font-500 py-2 text-center uppercase leading-tight tracking-[0.125ch]">
          <?= $image->caption() ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endforeach ?>
</div>
