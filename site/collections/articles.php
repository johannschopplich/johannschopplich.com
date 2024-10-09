<?php

return fn(\Kirby\Cms\App $kirby) => $kirby
    ->page('blog')
    ->children()
    ->listed()
    ->filter(fn($i) => $i->text()->isNotEmpty())
    ->sortBy(
        fn($i) => $i->published()->toDate(),
        'desc'
    );
