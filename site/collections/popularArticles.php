<?php

return function (\Kirby\Cms\App $kirby) {
    return $kirby
        ->site()
        ->popularArticles()
        ->toPages()
        ->sortBy(
            fn($i) => $i->published()->toDate(),
            'desc'
        );
};
