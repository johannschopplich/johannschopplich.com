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
        <figcaption class="font-size-7 lh-tight text-contrast-medium text-center px-m py-2">
          <?= $image->caption() ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endforeach ?>
</div>
