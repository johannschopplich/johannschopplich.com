<?php

return function (\Kirby\Cms\App $kirby, \Kirby\Cms\Page $page) {
    $query = $kirby
        ->collection('articles')
        ->paginate(6);

    return compact('query');
};
