<?php

use Kirby\Cms\App;
use Kirby\Cms\Html;
use Kirby\Http\Remote;

App::plugin('johannschopplich/website', [
    'options' => [
        'cache.npm' => true
    ],
    'siteMethods' => [
        'npmDownloadStats' => function (array $packageNames): array {
            $cache = $this->kirby()->cache('johannschopplich.website.npm');

            // Sorted so the key stays the same no matter in which order the
            // packages are listed – all languages share one cache entry.
            sort($packageNames);
            $cacheKey = 'stats-' . sha1(implode(',', $packageNames));

            $fetch = function () use ($packageNames): array {
                $stats = [];

                foreach ($packageNames as $packageName) {
                    try {
                        $response = Remote::get(
                            'https://api.npmjs.org/downloads/range/last-year/' . $packageName,
                            ['timeout' => 5]
                        );
                        $dailyDownloads = $response->code() === 200
                            ? array_column($response->json()['downloads'] ?? [], 'downloads')
                            : [];
                    } catch (Throwable) {
                        $dailyDownloads = [];
                    }

                    if ($dailyDownloads === []) {
                        continue;
                    }

                    // The API range ends mid-week, so a trailing partial chunk
                    // would fake a download cliff at the end of the sparkline.
                    $weeklyDownloads = array_map('array_sum', array_chunk($dailyDownloads, 7));
                    if (count($dailyDownloads) % 7 !== 0) {
                        array_pop($weeklyDownloads);
                    }

                    $stats[$packageName] = [
                        'monthlyDownloads' => array_sum(array_slice($dailyDownloads, -30)),
                        'weeklyDownloads' => $weeklyDownloads
                    ];
                }

                return $stats;
            };

            $store = function (array $stats) use ($cache, $cacheKey, $packageNames): void {
                if (count($stats) === count($packageNames)) {
                    $cache->set($cacheKey, [
                        'fetchedAt' => time(),
                        'stats' => $stats
                    ]);
                }
            };

            $cachedEntry = $cache->get($cacheKey);

            if (is_array($cachedEntry) && isset($cachedEntry['fetchedAt'], $cachedEntry['stats'])) {
                // Stale-while-revalidate: expired stats are served instantly
                // and refreshed after the response has been flushed.
                if ($cachedEntry['fetchedAt'] < time() - 60 * 60 * 24 * 7) {
                    register_shutdown_function(function () use ($fetch, $store): void {
                        if (function_exists('fastcgi_finish_request')) {
                            fastcgi_finish_request();
                        }
                        $store($fetch());
                    });
                }

                return $cachedEntry['stats'];
            }

            $stats = $fetch();
            $store($stats);

            return $stats;
        },
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
                'jobTitle' => 'Lead Software Engineer',
                'worksFor' => [
                    '@type' => 'Organization',
                    'name' => 'Finanzfluss',
                    'url' => 'https://www.finanzfluss.de/'
                ],
                'alumniOf' => [
                    '@type' => 'CollegeOrUniversity',
                    'name' => 'University of Greifswald',
                    'url' => 'https://www.uni-greifswald.de/'
                ],
                'sameAs' => [
                    'https://github.com/johannschopplich',
                    'https://www.linkedin.com/in/johann-schopplich/',
                    'https://www.instagram.com/johannschopplich/',
                    'https://x.com/jschopplich'
                ],
                'knowsAbout' => [
                    'Web Development',
                    'Frontend Architecture',
                    'TypeScript',
                    'Vue.js',
                    'Nuxt',
                    'React',
                    'Design Systems',
                    'Open Source Software',
                    'Laravel',
                    'Flutter',
                    'Cloudflare Workers',
                    'Kirby CMS'
                ]
            ];

            if ($this->description()->isNotEmpty()) {
                $person['description'] = $this->description()->value();
            }

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
        },
        // The canonical WebSite identity – language-stable like `personId` – so
        // every page node can declare itself `isPartOf` the one website entity.
        'webSiteId' => function (): string {
            return rtrim($this->kirby()->url('index'), '/') . '/#website';
        },
        'webSiteReference' => function (): array {
            return ['@id' => $this->webSiteId()];
        }
    ],
    'pageMethods' => [
        // Per-language `@id`s for this page's WebPage and breadcrumb nodes, so
        // content entities can reference them within the page's entity graph.
        'webPageId' => function (): string {
            return $this->url() . '#webpage';
        },
        'breadcrumbId' => function (): string {
            return $this->url() . '#breadcrumb';
        },
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

            return [
                '@id' => $this->breadcrumbId(),
                'itemListElement' => $items
            ];
        }
    ]
]);

if (!function_exists('icon')) {
    /**
     * Returns an SVG icon from the `assets/icons` directory.
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
    function dateFormatter(
        int $dateType = IntlDateFormatter::LONG,
        int $timeType = IntlDateFormatter::NONE
    ): IntlDateFormatter {
        static $formatters = [];
        $locale = App::instance()->languageCode() ?? 'en';
        $key = "{$locale}:{$dateType}:{$timeType}";
        return $formatters[$key] ??= IntlDateFormatter::create($locale, $dateType, $timeType);
    }
}

if (!function_exists('formatCount')) {
    /**
     * Compacts a count for display in the current language,
     * e.g. `10.5M` in English and `10,5 Mio.` in German.
     */
    function formatCount(int $count): string
    {
        return MessageFormatter::formatMessage(
            App::instance()->languageCode() ?? 'en',
            '{0, number, :: compact-short .#}',
            [$count]
        ) ?: (string)$count;
    }
}

if (!function_exists('renderMarkdown')) {
    /**
     * Builds clean Markdown output for LLM consumption by joining non-empty parts.
     */
    function renderMarkdown(string|null ...$parts): string
    {
        return implode("\n\n", array_filter($parts, fn ($p) => $p !== null && $p !== ''));
    }
}
