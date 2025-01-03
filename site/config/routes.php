<?php

use Kirby\Cms\App;
use Kirby\Content\Field;
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
                'feed-' . $type . '.json',
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
                        'modified' => time(),
                        'dateformat' => 'r',
                        'items' => $items
                    ];

                    if ($items->count()) {
                        $modified = $items->first()->modified($data['dateformat'], 'date');
                        $data['modified'] = $modified;

                        $datefield = $items->first()->{$data['datefield']}();
                        if ($datefield instanceof Field && $datefield->isNotEmpty()) {
                            $data['date'] = date($data['dateformat'], $datefield->toTimestamp());
                        }
                    } else {
                        $data['modified'] = site()->homePage()->modified();
                    }

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
