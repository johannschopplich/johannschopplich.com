<?php

use Kirby\Cms\Files;

/** @var \Kirby\Cms\Block $block */

/** @var \Kirby\Cms\Files */
$images = $block->images()->toFiles();

// Only sort if we have more than 2 images
if ($images->count() > 2) {
  $imageArray = $images->values();
  $firstImage = array_shift($imageArray);

  // Separate remaining images by orientation
  $verticalImages = [];
  $horizontalImages = [];

  foreach ($imageArray as $image) {
    $ratio = $image->width() / $image->height();

    // Consider images with ratio < 1 as vertical (portrait)
    if ($ratio < 1) {
      $verticalImages[] = $image;
    } else {
      $horizontalImages[] = $image;
    }
  }

  // Create a balanced distribution using alternating pattern
  $sortedMiddle = [];
  $verticalCount = count($verticalImages);
  $horizontalCount = count($horizontalImages);

  if ($verticalCount === 0) {
    // No vertical images, just use horizontal ones
    $sortedMiddle = $horizontalImages;
  } elseif ($horizontalCount === 0) {
    // No horizontal images, just use vertical ones
    $sortedMiddle = $verticalImages;
  } else {
    // Calculate how often to place a vertical image
    $ratio = $horizontalCount / $verticalCount;
    $verticalIndex = 0;
    $horizontalIndex = 0;
    $nextVerticalAt = $ratio / 2; // Start placing verticals at half the ratio

    for ($i = 0; $i < ($verticalCount + $horizontalCount); $i++) {
      $shouldPlaceVertical = $verticalIndex < $verticalCount &&
        ($i >= $nextVerticalAt || $horizontalIndex >= $horizontalCount);

      if ($shouldPlaceVertical) {
        $sortedMiddle[] = $verticalImages[$verticalIndex];
        $verticalIndex++;
        $nextVerticalAt += $ratio; // Next vertical placement
      } else {
        $sortedMiddle[] = $horizontalImages[$horizontalIndex];
        $horizontalIndex++;
      }
    }
  }


  $images = new Files([$firstImage, ...$sortedMiddle]);
}

snippet('components/masonry', [
  'query' => $images,
  'width' => 'clamp(25rem, 25vw, 30rem)'
]);
