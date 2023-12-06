<?php

return [
    'seo-preview' => [
        'props' => [
            'label' => fn ($label = 'SEO Preview') => $label
        ],
        'computed' => [
            'config' => function () {
                return $this->kirby()->option('johannschopplich.seo-helpers', []);
            },
            'site' => function () {
                return [
                    'title' => $this->kirby()->site()->title()->value(),
                    'description' => $this->kirby()->site()->description()->value(),
                    'url' => $this->kirby()->site()->url()
                ];
            }
        ]
    ],
    'content-translation' => [
        'props' => [
            'label' => fn ($label = 'Content Translation') => $label
        ],
        'computed' => [
            'config' => fn () => option('johannschopplich.seo-helpers', [])
        ]
    ]
];
