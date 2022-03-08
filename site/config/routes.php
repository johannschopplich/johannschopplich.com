<?php

return [
    [
        'pattern' => 'feeds/(:alpha)',
        'method' => 'GET',
        'action' => function ($type) {
            if (!in_array($type, ['rss', 'json'])) {
                return false;
            }

            // https://github.com/bnomei/kirby3-feed#usage-feed
            $options = [
                'feedurl' => url("feeds/{$type}"),
                'title' => t('feed.title'),
                'description' => t('feed.description'),
                'titlefield' => 'title',
                'datefield' => 'published',
                'textfield' => 'text',
                'snippet' => "feed/{$type}",
                'sort' => false
            ];

            return collection('articles')
                ->limit(10)
                ->feed($options);
        }
    ]
];
