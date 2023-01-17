<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

snippet('layouts/default', slots: true);

snippet('text');
snippet('popular', [
  'query' => $kirby->collection('popularArticles'),
  'heading' => t('articles.popular')
]);

endsnippet();
