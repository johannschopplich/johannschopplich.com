<?php

use Kirby\Cms\App;
use Kirby\Cms\Html;
use Kirby\Http\Url;
use Kirby\Toolkit\Str;

App::plugin('johannschopplich/personal-website', [
    'hooks' => [
        'kirbytags:before' => function ($text) {
            return str_replace('\(', '[[', str_replace('\)', ']]', $text));
        },

        'kirbytags:after' => function ($text) {
            return str_replace(']]', ')', str_replace('[[', '(', $text));
        }
    ],

    'tags' => [
        'image' => require __DIR__ . '/tags/image.php',
        'pattern' => require __DIR__ . '/tags/pattern.php'
    ]
]);

if (!function_exists('dateFormatter')) {
    function dateFormatter()
    {
        static $dateFormatter;
        return $dateFormatter ??= new IntlDateFormatter(
            kirby()->languageCode(),
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );
    }
}

if (!function_exists('icon')) {
    /**
     * Returns an SVG icon from the `assets/img/icons` directory
     */
    function icon(string $symbol, string|null $class = null)
    {
        $kirby = App::instance();
        $iconDir = $kirby->root('index') . '/assets/img/icons/';
        $symbolPath = Url::path($symbol, false);

        if (!str_ends_with($symbol, '.svg')) {
            return;
        }

        $svg = Html::svg($iconDir . $symbolPath);

        $attributes = Html::attr([
            'class' => $class,
            'aria-hidden' => 'true',
            'focusable' => 'false'
        ]);

        return Str::replace($svg, '<svg', '<svg ' . $attributes, 1);
    }
}
