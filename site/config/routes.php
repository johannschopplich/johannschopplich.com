<?php

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

            $items = collection('articles')->limit(10);
            $options = [
                'url' => site()->url(),
                'feedurl' => url("feeds/{$type}"),
                'title' => t('feed.title'),
                'description' => t('feed.description'),
                'link' => site()->url(),
                'titlefield' => 'title',
                'datefield' => 'published',
                'textfield' => 'text',
                'modified' => time(),
                'dateformat' => 'r',
                'items' => $items
            ];

            if ($items->count()) {
                $modified = $items->first()->modified($options['dateformat'], 'date');
                $options['modified'] = $modified;

                $datefield = $items->first()->{$options['datefield']}();
                if ($datefield instanceof Field && $datefield->isNotEmpty()) {
                    $options['date'] = date($options['dateformat'], $datefield->toTimestamp());
                }
            } else {
                $options['modified'] = site()->homePage()->modified();
            }

            // Generate feed content
            $content = trim(snippet("feed/{$type}", $options, true));

            // Set appropriate content type
            $contentType = $type === 'rss' ? 'application/rss+xml' : 'application/json';

            return new Response(
                $content,
                $contentType
            );
        }
    ]
];
