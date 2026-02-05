<?php

/** @var Kirby\Cms\Blocks|null $blocks */

use League\HTMLToMarkdown\HtmlConverter;

if (!$blocks || $blocks->isEmpty()) {
  return;
}

$converter = new HtmlConverter([
  'header_style' => 'atx',
  'strip_tags' => true,
  'strip_placeholder_links' => true,
  'use_autolinks' => false,
  'hard_break' => true,
  'remove_nodes' => 'script style figure',
]);

$html = $blocks->toHtml();
$markdown = $converter->convert($html);

// Clean up excessive newlines
$markdown = preg_replace('/\n{3,}/', "\n\n", $markdown);

echo trim($markdown);
