<?php

use Kirby\Toolkit\Html;

if (isset($image) && $image !== null) {
  echo Html::img($image->placeholderUri(), [
    'class' => $class ?? null,
    'loading' => 'lazy',
    'data-srcset' => $image->srcset(),
    'data-sizes' => 'auto',
    'data-zoomable' => $zoomable ?? null,
    'width' => $image->width(),
    'height' => $image->height(),
    'alt' => $image->alt()->escape()
  ]);
}
