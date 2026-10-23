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

// Replace each `<br>` inside a heading with a space, since a line break would end
// the ATX heading and push the rest into a paragraph.
$html = preg_replace_callback(
  '/<h([1-6])\b[^>]*>.*?<\/h\1>/s',
  fn ($match) => preg_replace('/<br\s*\/?>/i', ' ', $match[0]),
  $html
);

$markdown = $converter->convert($html);

// Clean up excessive newlines.
$markdown = preg_replace('/\n{3,}/', "\n\n", $markdown);

echo trim($markdown);
