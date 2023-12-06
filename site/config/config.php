<?php

return [

    'debug' => env('KIRBY_MODE') === 'development' || env('KIRBY_DEBUG', false),

    'yaml' => [
        'handler' => 'symfony'
    ],
    'date' => [
        'handler' => 'intl'
    ],

    'panel' => [
        'install' => env('KIRBY_PANEL_INSTALL', false),
        'slug' => env('KIRBY_PANEL_SLUG', 'panel')
    ],

    'routes' => require __DIR__ . '/routes.php',

    'languages' => true,
    'languages.detect' => true,

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
            'allowOrigin' => env('KIRBY_HEADLESS_ALLOW_ORIGIN', '*')
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

    'johannschopplich.seo-helpers' => [
        'syncableFields' => ['text', 'description'],
        'translatableFields' => ['text', 'description'],
        'translatableBlocks' => [
            'text' => ['text']
        ],
        'DeepL' => [
            'apiKey' => env('DEEPL_API_KEY')
        ]
    ],

    'johannschopplich.algolia-docsearch' => [
        'appId' => env('ALGOLIA_APP_ID'),
        'apiKey' => env('ALGOLIA_API_KEY'),
        'index' => 'johannschopplich',
        'content' => [
            'default' => function (\Kirby\Cms\Page $page, string|null $languageCode) {
                return strip_tags($page->content($languageCode)->text()->toBlocks()->toHtml());
            },
            'profile' => function (\Kirby\Cms\Page $page, string|null $languageCode) {
                return strip_tags($page->content($languageCode)->bio()->toBlocks()->toHtml())
                    . strip_tags($page->content($languageCode)->cv()->toLayouts()->toBlocks()->toHtml());
            }
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
