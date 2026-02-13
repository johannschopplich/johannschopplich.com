<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$infobox = implode(
  "\n",
  $page->details()->toStructure()->map(
    fn ($detail) => '- **' . $detail->label()->value() . ':** ' . $detail->value()->value()
  )->values()
);

echo renderMarkdown(
  snippet('llm/frontmatter', ['fields' => [
    ...($page->subtitle()->isNotEmpty() ? ['subtitle' => $page->subtitle()->value()] : [])
  ]], true),
  '# ' . $page->title()->value(),
  $page->subtitle()->isNotEmpty() ? '*' . $page->subtitle()->value() . '*' : null,
  $infobox,
  snippet('llm/blocks', ['blocks' => $page->text()->toBlocks()], true)
);
