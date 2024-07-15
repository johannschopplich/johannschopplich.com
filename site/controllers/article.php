<?php

return function (\Kirby\Cms\App $kirby, \Kirby\Cms\Page $page) {
    $textLanguageCode = null;
    $blocks = $page->text()->toBlocks();

    if ($blocks->isEmpty()) {
        $textLanguageCode = $kirby
            ->languages()
            ->not($kirby->languageCode())
            ->first()
            ->code();

        $blocks = $page
            ->content($textLanguageCode)
            ->text()
            ->toBlocks();
    }

    return compact('textLanguageCode', 'blocks');
};
