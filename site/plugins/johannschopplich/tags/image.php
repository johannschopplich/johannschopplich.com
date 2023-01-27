<?php

use Kirby\Cms\Html;
use Kirby\Cms\Url;

return [
    'attr' => [
        'alt',
        'caption',
        'class',
        'imgclass',
        'link',
        'linkclass',
        'rel',
        'target'
    ],
    'html' => function ($tag) {
        if ($tag->file = $tag->file($tag->value)) {
            $tag->src = $tag->file->url();
            $tag->alt ??= $tag->file->alt()->value() || null;
            $tag->caption ??= $tag->file->caption()->value();
        } else {
            $tag->src = Url::to($tag->value);
        }

        $link = function ($img) use ($tag) {
            if (empty($tag->link)) {
                return $img;
            }

            $link = $tag->file($tag->link)?->url();
            $link ??= $tag->link === 'self' ? $tag->src : $tag->link;

            return Html::a($link, [$img], [
                'rel'    => $tag->rel,
                'class'  => $tag->linkclass,
                'target' => $tag->target
            ]);
        };

        if ($tag->file) {
            $isFeed = preg_match('/feeds\/(?:rss|json)$/', Url::current());
            $img = Html::img(
                $isFeed ? $tag->file->resize(1024)->url() : $tag->file->blurhashUri(),
                [
                    'data-loading' => 'lazy',
                    'data-srcset' => $tag->file->srcset(),
                    'data-sizes' => 'auto',
                    'width' => $tag->file->width(),
                    'height' => $tag->file->height(),
                    'class' => $tag->imgclass,
                    'alt' => $tag->alt
                ]
            );
        } else {
            $img = Html::img($tag->src, [
                'class'  => $tag->imgclass,
                'alt'    => $tag->alt
            ]);
        }

        // Render KirbyText in caption
        if ($tag->caption) {
            $tag->caption = [$tag->kirby()->kirbytext($tag->caption, [
                'markdown' => ['inline' => true]
            ])];
        }

        return Html::figure([$link($img)], $tag->caption, [
            'class' => $tag->class
        ]);
    }
];
