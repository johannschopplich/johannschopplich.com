<?php

$defaultContentIndexFn = fn (\Kirby\Cms\Page $page, string|null $languageCode) => strip_tags($page->content($languageCode)->text()->toBlocks()->toHtml());

return [

    'debug' => env('KIRBY_MODE') === 'development' || env('KIRBY_DEBUG', false),
    'yaml.handler' => 'symfony',

    'panel' => [
        'install' => env('KIRBY_PANEL_INSTALL', false),
        'slug' => env('KIRBY_PANEL_SLUG', 'panel')
    ],

    'routes' => require __DIR__ . '/routes.php',

    'languages' => true,
    'languages.detect' => true,
    'date.handler' => 'intl',

    'cache' => [
        'pages' => [
            'active' => env('KIRBY_CACHE', false),
            'ignore' => fn (\Kirby\Cms\Page $page) => $page->kirby()->user() !== null
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

    'markdown' => [
        'extra' => true
    ],

    // Default to token-based authentication
    'kql' => [
        'auth' => 'bearer'
    ],

    'headless' => [
        // Disable overwriting of the default routes
        'routes' => false,
        'token' => env('KIRBY_HEADLESS_API_TOKEN'),

        'cors' => [
            'allowOrigin' => env('KIRBY_HEADLESS_ALLOW_ORIGIN', '*'),
            'allowMethods' => env('KIRBY_HEADLESS_ALLOW_METHODS', 'GET, POST, OPTIONS'),
            'allowHeaders' => env('KIRBY_HEADLESS_ALLOW_HEADERS', '*'),
            'maxAge' => env('KIRBY_HEADLESS_MAX_AGE', '86400')
        ]
    ],

    'johannschopplich.helpers' => [
        'redirects' => require __DIR__ . '/redirects.php',
        'meta' => [
            'defaults' => require __DIR__ . '/meta.php'
        ],
        'robots' => [
            'enable' => true
        ],
        'sitemap' => [
            'enable' => true,
            'exclude' => [
                'templates' => [
                    'linktree'
                ]
            ]
        ]
    ],

    'johannschopplich.algolia-docsearch' => [
        'appId' => env('ALGOLIA_APP_ID'),
        'apiKey' => env('ALGOLIA_API_KEY'),
        'index' => 'johannschopplich',
        'content' => [
            'article' => $defaultContentIndexFn,
            'default' => $defaultContentIndexFn,
            'home' => $defaultContentIndexFn,
            'photography' => $defaultContentIndexFn,
            'profile' => function (\Kirby\Cms\Page $page, string|null $languageCode) {
                return strip_tags($page->content($languageCode)->bio()->toBlocks()->toHtml())
                    . strip_tags($page->content($languageCode)->cv()->toLayouts->toBlocks()->toHtml());
            },
            'project' => $defaultContentIndexFn
        ],
        // Templates which should be indexed
        'templates' => [
            'article',
            'default',
            'home',
            'photography',
            'profile',
            'project'
        ],
        // Define the search hit label
        'label' => [
            'default' => [
                'de' => 'Seite',
                'en' => 'Page'
            ],
            'templates' => [
                'article' => [
                    'de' => 'Artikel',
                    'en' => 'Article'
                ],
                'project' => [
                    'de' => 'Projekt',
                    'en' => 'Project'
                ]
            ]
        ]
    ]

];
