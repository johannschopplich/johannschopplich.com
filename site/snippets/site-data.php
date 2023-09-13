<?php

$root = dirname(__DIR__, 2);
$data = [
    'algolia' => [
        'appId' => env('ALGOLIA_APP_ID'),
        'apiKey' => env('ALGOLIA_SEARCH_ONLY_API_KEY')
    ],
    'icons' => array_map(
        fn ($file) => basename($file),
        glob($root . '/public/assets/img/icons/*.svg')
    )
];

$serializedData = \Kirby\Data\Json::encode($data);

?>

<script type="application/json" data-site><?= $serializedData ?></script>
