<?php

return [

    'debug' => env('KIRBY_MODE') === 'development' || env('KIRBY_DEBUG', false),

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
        'quality' => '80',
        'srcsets' => [
            'default' => [360, 720, 1024, 1280, 1536]
        ]
    ],

    'markdown' => [
        'extra' => true
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
            'enable' => true
        ]
    ],

    'johannschopplich.algolia-docsearch' => [
        'appId' => env('ALGOLIA_APP_ID'),
        'apiKey' => env('ALGOLIA_API_KEY'),
        'index' => 'johannschopplich',
        // HTML tag name which contains a page's content or closure
        // which returns the text content for a given page
        'content' => 'main',
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
