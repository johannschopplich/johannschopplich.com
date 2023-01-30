<?php

$data = [
    'algolia' => [
        'appId' => env('ALGOLIA_APP_ID'),
        'apiKey' => env('ALGOLIA_SEARCH_ONLY_API_KEY')
    ]
];

$serializedData = \Kirby\Data\Json::encode($data);

?>

<script id="site-data" type="application/json"><?= $serializedData ?></script>
