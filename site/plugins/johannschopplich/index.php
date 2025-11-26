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

if (!function_exists('socialIcon')) {
    /**
     * Generates a UnoCSS icon class from a social media URL
     */
    function socialIcon(string $url): string
    {
        static $platformsRegistry = [
            'x.com' => 'x',
            'twitter.com' => 'x',
            'youtu.be' => 'youtube',
        ];

        $host = Url::host($url);
        if (!$host) {
            return '';
        }

        // Remove `www.` prefix
        $domain = preg_replace('/^www\./', '', $host);

        // Check for mapped platforms first
        if (isset($platformsRegistry[$domain])) {
            $platform = $platformsRegistry[$domain];
        } else {
            // Extract platform name from domain
            $platform = explode('.', $domain)[0];
        }

        return 'i-tabler-brand-' . $platform;
    }
}
