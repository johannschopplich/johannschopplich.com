<?php

use Kirby\Toolkit\Html;

/** @var \Kirby\Cms\File|null $image */
if (isset($image) && !$image) return;

echo Html::img($image->placeholderUri(), [
  'class' => $class ?? null,
  'loading' => 'lazy',
  'data-srcset' => $image->srcset(),
  'data-sizes' => 'auto',
  'data-zoomable' => $zoomable ?? null,
  'width' => $image->width(),
  'height' => $image->height(),
  'alt' => $image->alt()->isNotEmpty() ? $image->alt()->escape() : null
]);
