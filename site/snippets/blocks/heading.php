<?php

use Kirby\Toolkit\Html;
use Kirby\Toolkit\Str;

/** @var \Kirby\Cms\Block $block */
$level = $block->level()->or('h2');
$id = Str::slug(Str::unhtml($block->text()));
$model = $block->parent();
$text = $block->text();

// Render anchor links only for allowed templates
$anchorsInTemplates = ['article', 'project', 'photography'];
if (in_array($model?->intendedTemplate()?->name(), $anchorsInTemplates, true)) {
    $text = Html::tag('a', [$text], ['href' => "#{$id}"]);
}

echo Html::tag($level, [$text], ['id' => $id]);
