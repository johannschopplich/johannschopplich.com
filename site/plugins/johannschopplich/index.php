<?php

use Kirby\Cms\App;
use Kirby\Cms\Html;

App::plugin('johannschopplich/website', [
    'siteMethods' => [
        // Single source of truth for the site's structured-data identity.
        // Google processes JSON-LD per page and does not resolve a bare `@id`
        // to a node on another page, so the full Person is emitted on every
        // page (via the standalone `Person` node in config/meta.php) and
        // everything else references it by the same, language-stable `@id`.
        'personId' => function (): string {
            return rtrim($this->kirby()->url('index'), '/') . '/#person';
        },
        // A bare `@id` reference to the canonical Person, for author/publisher/
        // mainEntity slots that point back at the same entity.
        'personReference' => function (): array {
            return ['@id' => $this->personId()];
        },
        // The canonical Person node, emitted once per page.
        'person' => function (): array {
            $person = [
                '@type' => 'Person',
                '@id' => $this->personId(),
                'name' => 'Johann Schopplich',
                'url' => rtrim($this->kirby()->url('index'), '/') . '/',
                'sameAs' => [
                    'https://github.com/johannschopplich',
                    'https://www.linkedin.com/in/johann-schopplich/',
                    'https://www.instagram.com/johannschopplich/',
                    'https://x.com/jschopplich'
                ],
                'knowsAbout' => [
                    'Web Development',
                    'TypeScript',
                    'Vue.js',
                    'Nuxt',
                    'Open Source Software'
                ]
            ];

            $aboutPage = $this->find('about');
            $portrait = ($aboutPage?->thumbnail()->toFile() ?? $aboutPage?->image())?->resize(1200);
            if ($portrait) {
                $person['image'] = [
                    '@type' => 'ImageObject',
                    'url' => $portrait->url(),
                    'width' => $portrait->width(),
                    'height' => $portrait->height()
                ];
            }

            return $person;
        }
    ],
    'pageMethods' => [
        'breadcrumbList' => function (): array {
            $home = $this->site()->homePage();

            $crumbs = $this->is($home) ? [] : [$home];
            foreach ($this->parents()->flip() as $ancestor) {
                if (!$ancestor->is($home)) {
                    $crumbs[] = $ancestor;
                }
            }
            $crumbs[] = $this;

            $items = [];
            $position = 1;
            foreach ($crumbs as $crumb) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $crumb->title()->value(),
                    'item' => $crumb->url()
                ];
            }

            return ['itemListElement' => $items];
        }
    ]
]);

if (!function_exists('icon')) {
    /**
     * Returns an SVG icon from the `assets/icons` directory
     */
    function icon(string $symbol, string|null $class = null): string|null
    {
        static $iconDir;
        static $svgCache = [];

        $iconDir ??= App::instance()->root('index') . '/assets/icons/';
        $path = $iconDir . basename($symbol);

        $svg = $svgCache[$path] ??= Html::svg($path);

        if (!$svg) {
            return null;
        }

        $attributes = Html::attr(array_filter([
            'class' => $class,
            'aria-hidden' => 'true',
            'focusable' => 'false'
        ], fn ($v) => $v !== null));

        return preg_replace(
            '!^<svg([^>]*)>!i',
            '<svg$1 ' . $attributes . '>',
            $svg
        );
    }
}

if (!function_exists('dateFormatter')) {
    function dateFormatter(): IntlDateFormatter
    {
        static $dateFormatter;
        return $dateFormatter ??= IntlDateFormatter::create(
            App::instance()->languageCode(),
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );
    }
}

if (!function_exists('renderMarkdown')) {
    /**
     * Builds clean Markdown output for LLM consumption by joining non-empty parts
     */
    function renderMarkdown(string|null ...$parts): string
    {
        return implode("\n\n", array_filter($parts, fn ($p) => $p !== null && $p !== ''));
    }
}
