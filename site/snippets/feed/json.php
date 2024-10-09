<?php

$entries = [];
foreach ($items as $item) {
  $entries[] = [
    'id'             => $item->url(),
    'url'            => $item->{$urlfield}(),
    'title'          => $item->{$titlefield}()->value(),
    'content_html'   => $item->{$textfield}()->toBlocks()->toHtml(),
    'date_published' => date('c', $item->{$datefield}()->toTimestamp()),
    'date_modified'  => $item->modified('Y-m-d\TH:i:sP', 'date'),
  ];
}

$feed = [
  'version'       => 'https://jsonfeed.org/version/1',
  'title'         => $title,
  'description'   => $description,
  'home_page_url' => $url,
  'feed_url'      => $feedurl,
  'items'         => $entries,
];

echo \Kirby\Data\Json::encode($feed);
