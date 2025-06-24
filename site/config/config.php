<?php

use Kirby\Cms\Page;

return [

    'debug' => env('KIRBY_MODE') === 'development' || env('KIRBY_DEBUG', false),

    'yaml' => [
        'handler' => 'symfony'
    ],

    'date' => [
        'handler' => 'intl'
    ],

    'routes' => require __DIR__ . '/routes.php',

    'languages' => [
        'detect' => true
    ],

    'panel' => [
        'install' => env('KIRBY_PANEL_INSTALL', false),
        'slug' => env('KIRBY_PANEL_SLUG', 'panel'),
        'vue' => [
            'compiler' => false
        ]
    ],

    'cache' => [
        'pages' => [
            'active' => env('KIRBY_CACHE', false),
            'ignore' => fn(Page $page) => $page->kirby()->user() !== null
        ]
    ],

    'thumbs' => [
        'format' => 'webp',
        'quality' => 85,
        'presets' => [
            'default' => ['format' => 'webp', 'quality' => 85],
        ],
        'srcsets' => [
            'default' => [
                390, // iPhone 12/13/14
                430, // iPhone 15 Pro Max
                780, // iPhone 12/13/14 @2x
                860, // iPhone 15 Pro Max @2x
                1170, // iPhone 12/13/14 @3x
                1366, // Common laptop resolution
                1440, // MacBook, many modern laptops
                1920, // Full HD
                2580, // iPhone 15 Pro Max @3x
                2880, // MacBook @2x
            ]
        ]
    ],

    'kql' => [
        'auth' => 'bearer'
    ],

    'headless' => [
        'token' => env('KIRBY_HEADLESS_API_TOKEN'),

        'cors' => [
            'allowOrigin' => env('KIRBY_HEADLESS_ALLOW_ORIGIN', '*')
        ]
    ],

    'johannschopplich.helpers' => [
        'redirects' => require __DIR__ . '/redirects.php',
        'meta' => [
            'defaults' => require __DIR__ . '/meta.php'
        ],
        'robots' => [
            'enabled' => true
        ],
        'sitemap' => [
            'enabled' => true,
            'exclude' => [
                'templates' => [
                    'linktree'
                ]
            ]
        ]
    ],

    'johannschopplich.content-translator' => [
        'importFrom' => 'all',
        'DeepL' => [
            'apiKey' => env('DEEPL_API_KEY')
        ]
    ],

];
