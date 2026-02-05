<?php

use Kirby\Cms\App;
use Kirby\Cms\Html;

App::plugin('johannschopplich/website');

if (!function_exists('icon')) {
    /**
     * Returns an SVG icon from the `assets/icons` directory
     */
    function icon(string $symbol, string|null $class = null): string|null
    {
        static $iconDir;
        static $svgCache = [];

        $iconDir ??= App::instance()->root('index') . '/assets/icons/';
        $path = $iconDir . basename($symbol);

        $svg = $svgCache[$path] ??= Html::svg($path);

        if (!$svg) {
            return null;
        }

        $attributes = Html::attr(array_filter([
            'class' => $class,
            'aria-hidden' => 'true',
            'focusable' => 'false'
        ], fn ($v) => $v !== null));

        return preg_replace(
            '!^<svg([^>]*)>!i',
            '<svg$1 ' . $attributes . '>',
            $svg
        );
    }
}

if (!function_exists('dateFormatter')) {
    function dateFormatter(): IntlDateFormatter
    {
        static $dateFormatter;
        return $dateFormatter ??= IntlDateFormatter::create(
            App::instance()->languageCode(),
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );
    }
}

if (!function_exists('renderMarkdown')) {
    /**
     * Builds clean Markdown output for LLM consumption by joining non-empty parts
     */
    function renderMarkdown(string|null ...$parts): string
    {
        return implode("\n\n", array_filter($parts, fn ($p) => $p !== null && $p !== ''));
    }
}
