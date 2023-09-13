<?php

$root = dirname(__DIR__, 2);
$icons = array_map(
  fn ($file) => basename($file),
  glob($root . '/public/assets/img/icons/*.svg')
);
$filteredIcons = array_filter($icons, fn ($file) => !str_starts_with($file, '_'));

$data = [
    'algolia' => [
        'appId' => env('ALGOLIA_APP_ID'),
        'apiKey' => env('ALGOLIA_SEARCH_ONLY_API_KEY')
    ],
    'icons' => $filteredIcons
];

$serializedData = \Kirby\Data\Json::encode($data);

?>

<script type="application/json" data-site><?= $serializedData ?></script>
