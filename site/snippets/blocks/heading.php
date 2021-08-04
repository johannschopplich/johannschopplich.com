<?php

use Kirby\Toolkit\Html;
use Kirby\Toolkit\Str;

/** @var \Kirby\Cms\Block $block */
$level = $block->level()->or('h2');
$id = Str::slug(Str::unhtml($block->text()));
$model = $block->parent();

if (
  is_a($model, \Kirby\Cms\Page::class) &&
  str_starts_with($model->template()->name(), 'article')
) {
  $text = Html::link("#{$id}", $block->text());
} else {
  $text = $block->text();
}

echo Html::tag($level, [$text], ['id' => $id]);
