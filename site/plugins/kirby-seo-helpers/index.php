<?php

@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App;

App::plugin('johannschopplich/seo-helpers', [
    'sections' => require __DIR__ . '/extensions/sections.php',
    'translations' => require __DIR__ . '/extensions/translations.php',
    'api' => require __DIR__ . '/extensions/api.php'
]);
