<?php

/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

snippet('layouts/default', slots: true);

snippet('sections/text-section');
snippet('components/article-list', [
  'query' => $kirby->collection('popularArticles'),
  'heading' => t('articles.popular')
]);

endsnippet();
