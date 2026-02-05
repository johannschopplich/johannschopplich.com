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
    // Consider images with ratio < 1 as vertical (portrait)
    if ($image->width() / $image->height() < 1) {
      $verticalImages[] = $image;
    } else {
      $horizontalImages[] = $image;
    }
  }

  // Create a balanced distribution using golden ratio spacing
  $verticalCount = count($verticalImages);
  $horizontalCount = count($horizontalImages);
  $total = $verticalCount + $horizontalCount;

  if ($verticalCount === 0) {
    $sortedMiddle = $horizontalImages;
  } elseif ($horizontalCount === 0) {
    $sortedMiddle = $verticalImages;
  } else {
    $phi = (1 + sqrt(5)) / 2; // Golden ratio ≈ 1.618
    $sortedMiddle = array_fill(0, $total, null);

    // Place vertical images using golden ratio distribution
    for ($i = 0; $i < $verticalCount; $i++) {
      $position = (int) floor(fmod($i / $phi, 1) * $total);

      // Find nearest empty slot if position is taken
      while ($sortedMiddle[$position] !== null) {
        $position = ($position + 1) % $total;
      }
      $sortedMiddle[$position] = $verticalImages[$i];
    }

    // Fill remaining slots with horizontal images
    $hi = 0;
    for ($i = 0; $i < $total; $i++) {
      if ($sortedMiddle[$i] === null) {
        $sortedMiddle[$i] = $horizontalImages[$hi++];
      }
    }
  }


  $images = new Files([$firstImage, ...$sortedMiddle]);
}

snippet('components/masonry', [
  'query' => $images,
  'width' => 'clamp(25rem, 25vw, 30rem)'
]);
