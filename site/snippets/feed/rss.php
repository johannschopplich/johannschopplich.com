<?php

use Kirby\Toolkit\Xml;

echo '<?xml version="1.0" encoding="utf-8"?>';
echo "\n" . '<?xml-stylesheet type="text/xsl" href="/feeds/rss.xsl"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?= Xml::encode($title) ?></title>
    <link><?= Xml::encode($url) ?></link>
    <atom:link href="<?= Xml::encode($feedurl) ?>" rel="self" type="application/rss+xml"/>
    <lastBuildDate><?= $modified ?></lastBuildDate>
    <?php if (trim($description ?? '') !== ''): ?>
      <description><?= Xml::encode($description) ?></description>
    <?php endif ?>
    <?php foreach ($items as $item): ?>
      <item>
        <title><?= Xml::encode($item->{$titlefield}()) ?></title>
        <link><?= Xml::encode($item->url()) ?></link>
        <guid><?= Xml::encode($item->url()) ?></guid>
        <pubDate><?= $datefield === 'modified' ? $item->modified('r', 'date') : date('r', $item->{$datefield}()->toTimestamp()) ?></pubDate>
        <description>
          <![CDATA[<?= $item->{$textfield}()->toBlocks() ?>]]>
        </description>
      </item>
    <?php endforeach ?>
  </channel>
</rss>
