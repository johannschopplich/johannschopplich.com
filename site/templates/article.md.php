<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

echo renderMarkdown(
  snippet('llm/frontmatter', ['fields' => [
    'date' => $page->published()->toDate('Y-MM-dd'),
    ...($page->categories()->isNotEmpty() ? ['categories' => array_map('trim', explode(',', $page->categories()->value()))] : [])
  ]], true),
  '# ' . $page->title()->value(),
  snippet('llm/blocks', ['blocks' => $page->text()->toBlocks()], true)
);
