<?php

use Kirby\Http\Response;

return [
    [
        'pattern' => 'feeds/(:alpha)',
        'method' => 'GET',
        'action' => function ($type) {
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
            $contentType = $type === 'rss' ? 'application/rss+xml' : 'application/json';

            return new Response(
                $content,
                $contentType
            );
        }
    ]
];
