<?php

use Kirby\Toolkit\Html;

if (isset($image) && $image !== null) {
  echo Html::img($image->placeholderUri(), [
    'class' => $class ?? null,
    'data-srcset' => $image->srcset(),
    'data-sizes' => 'auto',
    'data-lazyload' => 'true',
    'data-zoomable' => $zoomable ?? null,
    'width' => $image->width(),
    'height' => $image->height(),
    'alt' => $image->alt()->escape()
  ]);
}
