<?php

return function (\Kirby\Cms\App $kirby, \Kirby\Cms\Page $page) {
    $lang = null;
    $blocks = $page->text()->toBlocks();

    if ($blocks->isEmpty()) {
        $lang = $kirby
            ->languages()
            ->not($kirby->languageCode())
            ->first()
            ?->code();

        $blocks = $page
            ->content($lang)
            ->text()
            ->toBlocks();
    }

    return compact('lang', 'blocks');
};
