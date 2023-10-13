<?php

use Kirby\Cms\App;
use Kirby\Cms\Html;
use Kirby\Http\Url;

App::plugin('johannschopplich/website', [
    'tags' => [
        'image' => require __DIR__ . '/tags/image.php',
        'pattern' => require __DIR__ . '/tags/pattern.php'
    ]
]);

if (!function_exists('icon')) {
    /**
     * Returns an SVG icon from the `assets/icons` directory
     */
    function icon(string $symbol, string|null $class = null)
    {
        $kirby = App::instance();
        $iconDir = $kirby->root('index') . '/assets/icons/';
        $symbolPath = Url::path($symbol);
        $svg = Html::svg($iconDir . $symbolPath);

        if (!$svg) {
            return;
        }

        $attributes = Html::attr([
            'class' => $class,
            'aria-hidden' => 'true',
            'focusable' => 'false'
        ]);

        return preg_replace(
            '!^<svg([^>]*)>!i',
            '<svg$1 ' . $attributes . '>',
            $svg
        );
    }
}

if (!function_exists('dateFormatter')) {
    function dateFormatter()
    {
        static $dateFormatter;
        return $dateFormatter ??= new IntlDateFormatter(
            App::instance()->languageCode(),
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );
    }
}
