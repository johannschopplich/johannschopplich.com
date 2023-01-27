<?php

\Kirby\Cms\App::plugin('johannschopplich/website', [
    'fieldMethods' => [
        'anchorHeadlines' => require __DIR__ . '/fields/anchorHeadlines.php'
    ],

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
