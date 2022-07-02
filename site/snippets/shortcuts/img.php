<?php

use Kirby\Toolkit\Html;

/** @var \Kirby\Cms\File|null $file */
if (isset($file) && !$file) {
    return;
}

echo Html::img($file->placeholderUri(), [
  'class' => $class ?? null,
  'data-loading' => 'lazy',
  'data-srcset' => $file->srcset(),
  'data-sizes' => 'auto',
  'data-zoomable' => $zoomable ?? null,
  'width' => $file->width(),
  'height' => $file->height(),
  'alt' => $file->alt()->isNotEmpty() ? $file->alt()->escape() : null
]);
