<?php

use Kirby\Cms\Page;

return [

    'url' => env('KIRBY_URL'),

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
        'slug' => env('KIRBY_PANEL_SLUG', 'panel')
    ],

    'cache' => [
        'pages' => [
            'active' => env('KIRBY_CACHE', false),
            'ignore' => fn(Page $page) => $page->kirby()->user() !== null
        ]
    ],

    'thumbs' => [
        'format' => 'webp',
        'quality' => 80,
        'presets' => [
            'default' => ['format' => 'webp', 'quality' => 80],
        ],
        'srcsets' => [
            'default' => [360, 720, 1024, 1280, 1536]
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
