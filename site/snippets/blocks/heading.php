<?php

use Kirby\Toolkit\Html;
use Kirby\Toolkit\Str;

/** @var \Kirby\Cms\Block $block */

$level = $block->level()->or('h2');
$id = Str::slug(Str::unhtml($block->text()));
/** @var \Kirby\Cms\Site|\Kirby\Cms\Page|null */
$model = $block->parent();
$text = $block->text()->permalinksToUrls();

$anchorsInTemplates = ['article', 'project', 'photography'];
// A link inside the heading would nest in the anchor, which HTML forbids.
$hasLink = str_contains($text->value(), '<a ');
if (!$hasLink && in_array($model?->intendedTemplate()?->name(), $anchorsInTemplates, true)) {
  $text = Html::tag('a', [$text], ['href' => "#{$id}"]);
}

echo Html::tag($level, [$text], ['id' => $id]);
