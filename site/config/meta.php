<?php

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

return fn (App $kirby, Site $site, Page $page) => [
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
                    'https://github.com/johannschopplich',
                    'https://www.linkedin.com/in/johann-schopplich/',
                    'https://www.instagram.com/johannschopplich/',
                    'https://x.com/jschopplich'
                ]
            ]
        ]
    ]
];
