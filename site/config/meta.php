<?php

return fn (\Kirby\Cms\App $kirby, \Kirby\Cms\Site $site, \Kirby\Cms\Page $page) => [
    'jsonld' => [
        'WebSite' => [
            'name' => $site->title()->value(),
            'description' => $site->description()->value(),
            'url' => url(),
            'author' => [
                '@type' => 'Person',
                'name' => 'Johann Schopplich',
                'url' => url(),
                'gender' => 'male',
                'sameAs' => [
                    'https://www.instagram.com/johannschopplich/',
                    'https://twitter.com/johannschopplich',
                    'https://github.com/johannschopplich'
                ]
            ],
        ]
    ]
];
