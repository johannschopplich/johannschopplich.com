<?php

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

return function (App $kirby, Site $site, Page $page): array {
    return [
        'jsonld' => [
            'Person' => $site->person(),
            'WebSite' => [
                '@id' => $site->webSiteId(),
                'name' => $site->title()->value(),
                'description' => $site->description()->value(),
                'url' => $site->url(),
                'inLanguage' => $kirby->languageCode(),
                'author' => $site->personReference()
            ],
            'WebPage' => [
                '@id' => $page->webPageId(),
                'url' => $page->url(),
                'name' => $page->customTitle()->or($page->title())->value(),
                'inLanguage' => $kirby->languageCode(),
                'isPartOf' => $site->webSiteReference()
            ]
        ]
    ];
};
