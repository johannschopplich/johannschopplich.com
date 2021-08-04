<?php

return function (\Kirby\Cms\App $kirby) {
    return $kirby
        ->page('blog')
        ->children()
        ->listed()
        ->filter(fn($i) => $i->text()->toBlocks()->isNotEmpty())
        ->sortBy(
            fn($i) => $i->published()->toDate(),
            'desc'
        );
};
