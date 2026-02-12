<?php

use Kirby\Cms\Language;
use Kirby\Exception\NotFoundException;
use Kirby\Http\Response;

return [
    // Serve .md.php content representations when `Accept: text/markdown` is requested
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
                return $page->render([], 'md');
            } catch (NotFoundException) {
                $this->next();
            }
        }
    ],
    [
        'pattern' => 'feeds/(:alpha)',
        'method'  => 'GET',
        'action'  => function ($type) {
            if (!in_array($type, ['rss', 'json'])) {
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

                    // Generate feed content
                    return trim(snippet("feed/{$type}", $data, true));
                }
            );

            // Set appropriate content type
            $contentType = match ($type) {
                'rss' => 'application/rss+xml',
                'json' => 'application/json',
            };

            return new Response(
                $content,
                $contentType
            );
        }
    ]
];
