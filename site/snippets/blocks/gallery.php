<?php

use Kirby\Cms\Files;

/** @var \Kirby\Cms\Block $block */

/** @var \Kirby\Cms\Files */
$images = $block->images()->toFiles();

// Only sort if we have more than 2 images
if ($images->count() > 2) {
  $imageArray = $images->values();
  $firstImage = array_shift($imageArray);
  $lastImage = array_pop($imageArray);

  // Separate middle images by orientation
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

  // Create a balanced distribution
  $sortedMiddle = [];
  $totalMiddle = count($verticalImages) + count($horizontalImages);

  if ($totalMiddle > 0) {
    $verticalIndex = 0;
    $horizontalIndex = 0;

    // If we have vertical images, distribute them evenly
    if (count($verticalImages) > 0) {
      $verticalSpacing = $totalMiddle / count($verticalImages);

      for ($i = 0; $i < $totalMiddle; $i++) {
        // Place vertical image if we're at the right interval or ran out of horizontal images
        $shouldPlaceVertical = count($verticalImages) > $verticalIndex &&
          ((fmod($i, $verticalSpacing) < 1) || count($horizontalImages) <= $horizontalIndex);

        if ($shouldPlaceVertical) {
          $sortedMiddle[] = $verticalImages[$verticalIndex];
          $verticalIndex++;
        } elseif (count($horizontalImages) > $horizontalIndex) {
          $sortedMiddle[] = $horizontalImages[$horizontalIndex];
          $horizontalIndex++;
        }
      }
    } else {
      // No vertical images, just add all horizontal ones
      $sortedMiddle = $horizontalImages;
    }

    // Add any remaining images (fallback)
    while ($verticalIndex < count($verticalImages)) {
      $sortedMiddle[] = $verticalImages[$verticalIndex];
      $verticalIndex++;
    }
    while ($horizontalIndex < count($horizontalImages)) {
      $sortedMiddle[] = $horizontalImages[$horizontalIndex];
      $horizontalIndex++;
    }
  }

  // Reconstruct the final array with first, sorted middle, and last
  $finalImages = [$firstImage];
  $finalImages = array_merge($finalImages, $sortedMiddle);
  $finalImages[] = $lastImage;

  $images = new Files($finalImages);
}

snippet('components/masonry', [
  'query' => $images,
  'width' => 'clamp(25rem, 25vw, 30rem)'
]);
