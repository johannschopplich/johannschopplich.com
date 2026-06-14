<?php

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

return fn (App $kirby, Site $site, Page $page) => [
    'jsonld' => [
        'Person' => $site->person(),
        'WebSite' => [
            'name' => $site->title()->value(),
            'description' => $site->description()->value(),
            'url' => $site->url(),
            'inLanguage' => $kirby->languageCode(),
            'author' => $site->personReference()
        ]
    ]
];
