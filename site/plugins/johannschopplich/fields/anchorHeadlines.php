<?php

use Kirby\Cms\Field;
use Kirby\Cms\Html;
use Kirby\Toolkit\Str;

return function (Field $field, $headlines = 'h2|h3') {
    $headlinesPattern = is_array($headlines) ? implode('|', $headlines) : $headlines;

    // Add anchors to headlines
    $field->value = preg_replace_callback('/<(' . $headlinesPattern . ')>(.*?)<\/\\1>/s', function ($match) {
        $text = Str::unhtml($match[2]);
        $id = Str::slug($text);
        $link = Html::a("#{$id}", $text);
        return Html::tag($match[1], [$link], ['id' => $id]);
    }, $field->value);

    return $field;
};
