<?php

use Kirby\Cms\App;
use Kirby\Cms\Html;
use Kirby\Http\Url;

App::plugin('johannschopplich/website');

if (!function_exists('icon')) {
    /**
     * Returns an SVG icon from the `assets/icons` directory
     */
    function icon(string $symbol, string|null $class = null)
    {
        static $iconDir;
        if (!$iconDir) {
            $kirby = App::instance();
            $iconDir = $kirby->root('index') . '/assets/icons/';
        }

        $svg = Html::svg($iconDir . Url::path($symbol));

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
        return $dateFormatter ??= IntlDateFormatter::create(
            App::instance()->languageCode(),
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );
    }
}
