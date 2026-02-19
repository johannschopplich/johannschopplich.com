<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

$kirby->response()->type('text/markdown');

$details = implode(
  "\n",
  $page->details()->toStructure()->map(
    function ($detail) {
      $parts = [];
      if ($detail->text()->isNotEmpty()) {
        $parts[] = $detail->text()->value();
      }
      if ($file = $detail->file()->toFile()) {
        $parts[] = '[' . $file->filename() . '](' . $file->url() . ')';
      }
      return '- **' . $detail->label()->value() . ':** ' . implode(' ', $parts);
    }
  )->values()
);

echo renderMarkdown(
  snippet('llm/frontmatter', ['fields' => [
    ...($page->subtitle()->isNotEmpty() ? ['subtitle' => $page->subtitle()->value()] : [])
  ]], true),
  '# ' . $page->title()->value(),
  $page->subtitle()->isNotEmpty() ? '*' . $page->subtitle()->value() . '*' : null,
  $details,
  snippet('llm/blocks', ['blocks' => $page->text()->toBlocks()], true)
);
