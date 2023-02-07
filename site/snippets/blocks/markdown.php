<?php

use Kirby\Cms\Html;
use Kirby\Toolkit\Str;

/** @var \Kirby\Cms\Block $block */
$field = $block->text()->kt();
$headlines = ['h2', 'h3'];

// Add anchors to headlines
$text = preg_replace_callback(
    '/<(' . implode('|', $headlines) . ')>(.*?)<\/\\1>/s',
    function ($match) {
        $text = Str::unhtml($match[2]);
        $id = Str::slug($text);
        $link = Html::a("#{$id}", $text);
        return Html::tag($match[1], [$link], ['id' => $id]);
    },
    $field->value
);
?>
<?= $text ?>
