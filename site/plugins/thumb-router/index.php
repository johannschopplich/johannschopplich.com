<?php

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Http\Response;

App::plugin('johannschopplich/thumb-router', [
    'options' => [
        // Restrict image transformations to files of specific pages
        // (by page URI). An empty array allows all pages.
        'allowedPages' => []
    ],
    'routes' => [
        [
            'pattern'  => 'thumbs/(:all)',
            'language' => '*',
            // Use variadic args because the call signature differs:
            // - Multi-lang: `LanguageRouter` passes (`Language`, string)
            // - Single-lang: `Router` passes (string)
            'action'   => function (...$args) {
                $kirby = App::instance();
                $imagePath = $kirby->multilang() ? $args[1] : $args[0];

                if (empty($imagePath)) {
                    return false;
                }

                $image = $kirby->image($imagePath);

                if (!$image) {
                    return false;
                }

                $allowedPages = $kirby->option('johannschopplich.thumb-router.allowedPages', []);

                if (!empty($allowedPages)) {
                    $parent = $image->parent();

                    if (
                        !$parent instanceof Page ||
                        !in_array($parent->id(), $allowedPages, true)
                    ) {
                        return false;
                    }
                }

                $allowedTransformations = [
                    'blur' => false,
                    'crop' => true,
                    'format' => true,
                    'grayscale' => false,
                    'height' => [
                        390, // iPhone 12/13/14
                        430, // iPhone 15 Pro Max
                        780, // iPhone 12/13/14 @2x
                        860, // iPhone 15 Pro Max @2x
                        1170, // iPhone 12/13/14 @3x
                        1366, // Common laptop resolution
                        1440, // MacBook, many modern laptops
                        1920, // Full HD
                        2580, // iPhone 15 Pro Max @3x
                        2880, // MacBook @2x
                    ],
                    'quality' => false,
                    'sharpen' => false,
                    'width' => [
                        390, // iPhone 12/13/14
                        430, // iPhone 15 Pro Max
                        780, // iPhone 12/13/14 @2x
                        860, // iPhone 15 Pro Max @2x
                        1170, // iPhone 12/13/14 @3x
                        1366, // Common laptop resolution
                        1440, // MacBook, many modern laptops
                        1920, // Full HD
                        2580, // iPhone 15 Pro Max @3x
                        2880, // MacBook @2x
                    ]
                ];

                $thumbOptions = [];

                foreach ($kirby->request()->query()->toArray() as $key => $value) {
                    if (!isset($allowedTransformations[$key])) {
                        continue;
                    }

                    $parsedValue = match (true) {
                        $value === null => null,
                        is_numeric($value) => (int)$value,
                        strtolower($value) === 'true'  => true,
                        strtolower($value) === 'false' => false,
                        strtolower($value) === 'null'  => null,
                        default => $value,
                    };

                    if ($parsedValue === null) {
                        continue;
                    }

                    $allowedValues = $allowedTransformations[$key];

                    if ($allowedValues === true || (is_array($allowedValues) && in_array($parsedValue, $allowedValues, true))) {
                        $thumbOptions[$key] = $parsedValue;
                    }
                }

                if (!empty($thumbOptions)) {
                    $image = $image->thumb($thumbOptions);
                }

                return Response::redirect($image->url());
            }
        ]
    ]
]);
