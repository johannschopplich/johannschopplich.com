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

    'johannschopplich.algolia-doc-search' => [
        'app' => env('ALGOLIA_APP_ID'),
        'key' => env('ALGOLIA_INDEX_KEY'),
        'index' => 'johannschopplich',
        // Define the content that should be indexed
        'content' => function (\Kirby\Cms\Page $page) {
            $html = $page->render();
            // Extract only the HTML inside the <main> tag
            $main = preg_replace('/^.*<main[^>]*>|<\/main>.*$/is', '', $html);
            // Strip all HTML tags
            return  strip_tags($main);
        },
        // Define templates which should be indexed
        'templates' => [
            'article',
            'articles',
            'default',
            'home',
            'photography',
            'profile',
            'project',
            'projects'
        ],
        'hierarchy' => [
            'default' => [
                'de' => 'Seite',
                'en' => 'Page'
            ],
            'templates' => [
                'article' => [
                    'de' => 'Artikel',
                    'en' => 'Article'
                ]
            ]
        ]
    ]

];
