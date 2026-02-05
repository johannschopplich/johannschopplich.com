<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$parts = [
  snippet('llm/frontmatter', [], true),
  '# ' . $page->title()->value(),
  snippet('llm/blocks', ['blocks' => $page->bio()->toBlocks()], true),
  '## Career',
];

foreach ($page->career()->toStructure() as $entry) {
  $parts[] = '### ' . $entry->company()->value() . ' – ' . $entry->role()->value();
  $parts[] = '**Period:** ' . $entry->period()->value();
  if ($entry->url()->isNotEmpty()) {
    $parts[] = '**Website:** ' . $entry->url()->value();
  }
  if ($entry->description()->toBlocks()->isNotEmpty()) {
    $parts[] = snippet('llm/blocks', ['blocks' => $entry->description()->toBlocks()], true);
  }
}

echo renderMarkdown(...$parts);
