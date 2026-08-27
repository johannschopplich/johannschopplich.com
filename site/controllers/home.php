<?php

return function (\Kirby\Cms\App $kirby, \Kirby\Cms\Site $site, \Kirby\Cms\Page $page) {
    $kirby->response()->cache(false);

    $packageItems = $page->packages()->toStructure();
    $packageNames = $packageItems->values(fn ($item) => $item->name()->value());

    $stats = $packageNames === [] ? [] : $site->npmDownloadStats($packageNames);

    $packages = [];
    foreach ($packageItems as $item) {
        $name = $item->name()->value();
        if (!isset($stats[$name])) continue;

        $packages[] = [
            'name' => $name,
            'url' => $item->url()->value(),
            'description' => $item->description()->value(),
            'monthlyDownloads' => $stats[$name]['monthlyDownloads'],
            'weeklyDownloads' => $stats[$name]['weeklyDownloads']
        ];
    }

    return compact('packages');
};
