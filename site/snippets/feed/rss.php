<?php

use Kirby\Toolkit\Xml;

echo '<?xml version="1.0" encoding="utf-8"?>';
?><rss version="2.0">
  <channel>
    <title><?= Xml::encode($title) ?></title>
    <link><?= Xml::encode($url) ?></link>
    <lastBuildDate><?= $modified ?></lastBuildDate>
    <?php if ($description && is_string($description) && strlen(trim($description)) > 0): ?>
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