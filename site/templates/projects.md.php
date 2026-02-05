<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$parts = [
  snippet('llm/frontmatter', [], true),
  snippet('llm/blocks', ['blocks' => $page->text()->toBlocks()], true),
];

foreach ($page->children()->listed() as $project) {
  $parts[] = '## ' . $project->title()->value();
  $parts[] = '- **URL:** ' . $project->url();
  if ($project->subtitle()->isNotEmpty()) {
    $parts[] = '*' . $project->subtitle()->value() . '*';
  }
  if ($project->description()->isNotEmpty()) {
    $parts[] = $project->description()->value();
  }
}

echo renderMarkdown(...$parts);
