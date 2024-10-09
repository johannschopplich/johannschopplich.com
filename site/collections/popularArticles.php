<?php

return fn(\Kirby\Cms\App $kirby) => $kirby
    ->site()
    ->popularArticles()
    ->toPages()
    ->sortBy(
        fn($i) => $i->published()->toDate(),
        'desc'
    );
