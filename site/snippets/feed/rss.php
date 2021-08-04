<?php
use Kirby\Toolkit\Xml;
echo '<?xml version="1.0" encoding="utf-8"?>';
?><rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?= Xml::encode($title) ?></title>
    <link><?= Xml::encode($link) ?></link>
    <lastBuildDate><?= $modified ?></lastBuildDate>
    <atom:link href="<?= Xml::encode($feedurl) ?>" rel="self" type="application/rss+xml" />
    <?php if ($description && is_string($description) && strlen(trim($description)) > 0): ?>
      <description><?= Xml::encode($description) ?></description>
    <?php endif; ?>
    <?php foreach ($items as $item): ?>
      <item>
        <title><?= Xml::encode($item->{$titlefield}()) ?></title>
        <link><?= Xml::encode($item->{$urlfield}()) ?></link>
        <guid><?= Xml::encode($item->url()) ?></guid>
        <pubDate><?= $datefield === 'modified' ? $item->modified('r', 'date') : date('r', $item->{$datefield}()->toTimestamp()) ?></pubDate>
        <description><![CDATA[<?= $item->{$textfield}()->toBlocks() ?>]]></description>
      </item>
    <?php endforeach; ?>
  </channel>
</rss>
