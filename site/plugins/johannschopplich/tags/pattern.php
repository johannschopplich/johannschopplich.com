<?php

use Kirby\Cms\Html;
use Kirby\Cms\Url;

return [
    'attr' => [
        'size',
        'caption',
        'class'
    ],
    'html' => function ($tag) {
        if ($tag->file = $tag->file($tag->value)) {
            $tag->src = $tag->file->url();
            $tag->caption ??= $tag->file->caption()->value();
        } else {
            $tag->src = Url::to($tag->value);
        }

        $tag->size ??= 'initial';

        $image = Html::tag('div', [], [
            'style' => "height: 16rem; background-image: url({$tag->src}); background-size: {$tag->size};"
        ]);

        return Html::figure([$image], $tag->caption, [
            'class' => trim('is-outset background-pattern ' . $tag->class, ' ')
        ]);
    }
];
