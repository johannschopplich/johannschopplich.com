<?php
/** @var \Kirby\Cms\Page $page */
/** @var \Kirby\Cms\Files $query */
?>
<div <?= attr([
  'class' => 'grid-masonry is-photography',
  'style' => $page->isHomePage() ? '--masonry-max-width: 40vw; --masonry-gap: var(--space-m);' : null
]) ?>>
  <?php foreach ($query as $image): ?>
    <?php /** @var \Kirby\Cms\File $image */ ?>
    <figure>
      <?php snippet('image', [
        'image' => $image,
        'zoomable' => true
      ]) ?>

      <?php if ($image->caption()->isNotEmpty() && !$page->isHomePage()): ?>
        <figcaption class="text-7 lh-heading text-center px-m py-xs">
          <?= $image->caption() ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endforeach ?>
</div>
