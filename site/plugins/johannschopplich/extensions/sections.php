<?php

return [
    'seo-preview' => [
        'props' => [
            'label' => fn ($label = 'SEO Preview') => $label
        ],
        'computed' => [
            'config' => function () {
                return $this->kirby()->option('johannschopplich.seo-preview', []);
            },
            'site' => function () {
                return [
                    'title' => $this->kirby()->site()->title()->value(),
                    'description' => $this->kirby()->site()->description()->value(),
                    'url' => $this->kirby()->site()->url()
                ];
            }
        ]
    ]
];
