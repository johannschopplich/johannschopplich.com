<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */
/** @var array $packages */

$kirby->response()->type('text/markdown');

$parts = [
  snippet('llm/frontmatter', [], true),
  snippet('llm/blocks', ['blocks' => $page->text()->toBlocks()], true),
  implode("\n", $page->socialLinks()->toStructure()->values(
    fn ($social) => '- [' . $social->platform()->value() . '](' . $social->url()->value() . ')'
  )),
  '## Sections',
  implode("\n", $site->children()->listed()->values(
    fn ($child) => '- [' . $child->title()->value() . '](' . $child->markdownUrl() . ')'
  )),
];

if ($packages !== []) {
  $parts[] = '## Packages';

  foreach ($packages as $package) {
    $parts[] = implode("\n", [
      '### ' . $package['name'],
      '- **URL:** ' . $package['url'],
      '- **Monthly downloads:** ' . $package['monthlyDownloads'],
      '',
      html_entity_decode(strip_tags($package['description']))
    ]);
  }
}

echo renderMarkdown(...$parts);
