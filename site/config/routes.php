<?php

use Kirby\Cms\Language;
use Kirby\Exception\NotFoundException;
use Kirby\Http\Response;

return [
    [
        'pattern'  => 'feeds/rss.xsl',
        'language' => '*',
        'method'   => 'GET',
        'action'   => fn (Language $language) => new Response(
            snippet('feed/rss.xsl', [], true),
            'text/xml'
        )
    ],
    // Serve `md` content representations when `Accept: text/markdown` is requested.
    [
        'pattern'  => '(:all)',
        'language' => '*',
        'method'   => 'GET',
        'action'   => function (Language $language, string $path = '') {
            $accept = kirby()->request()->header('Accept');

            if (!$accept || !str_contains($accept, 'text/markdown')) {
                $this->next();
            }

            $page = $path === '' ? site()->homePage() : page($path);

            if (!$page) {
                $this->next();
            }

            try {
                return $page->render(contentType: 'md');
            } catch (NotFoundException) {
                $this->next();
            }
        }
    ],
    [
        'pattern' => 'feeds/(:alpha)',
        'method'  => 'GET',
        'action'  => function ($type) {
            if (!in_array($type, ['rss', 'json'], true)) {
                return false;
            }

            $content = kirby()->cache('pages')->getOrSet(
                'feed-' . $type,
                function () use ($type) {
                    $items = collection('articles')->limit(10);

                    $data = [
                        'url' => site()->url(),
                        'feedurl' => url("feeds/{$type}"),
                        'title' => t('feed.title'),
                        'description' => t('feed.description'),
                        'titlefield' => 'title',
                        'datefield' => 'published',
                        'textfield' => 'text',
                        'modified' => $items->count()
                            ? $items->first()->modified('r', 'date')
                            : site()->homePage()->modified('r', 'date'),
                        'items' => $items
                    ];

                    return trim(snippet("feed/{$type}", $data, true));
                }
            );

            $contentType = match ($type) {
                'rss' => 'application/xml',
                'json' => 'application/json',
            };

            return new Response(
                $content,
                $contentType
            );
        }
    ]
];
