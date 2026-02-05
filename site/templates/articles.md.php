<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$parts = [
  snippet('llm/frontmatter', [], true),
  '# ' . $page->title()->value(),
];

foreach ($page->children()->listed()->sortBy('published', 'desc') as $article) {
  $parts[] = '## ' . $article->title()->value();
  $parts[] = implode("\n", [
    '- **URL:** ' . $article->url(),
    '- **Date:** ' . $article->published()->toDate('Y-MM-dd')
  ]);
  if ($article->description()->isNotEmpty()) {
    $parts[] = $article->description()->value();
  }
}

echo renderMarkdown(...$parts);
