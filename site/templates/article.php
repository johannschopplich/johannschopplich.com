<?php
/** @var \Kirby\Cms\App $kirby */
/** @var \Kirby\Cms\Site $site */
/** @var \Kirby\Cms\Page $page */

snippet('header');
snippet('text');
snippet('comments');
snippet('popular', [
  'query' => $kirby->collection('popularArticles'),
  'heading' => t('articles.popular')
]);
snippet('footer');
