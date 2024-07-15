<?php
/** @var \Kirby\Cms\Block $block */
snippet('components/masonry', [
    'query' => $block->images()->toFiles(),
    'width' => 'clamp(25rem, 25vw, 30rem)'
]);
