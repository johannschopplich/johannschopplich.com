<?php

return function (\Kirby\Cms\App $kirby, \Kirby\Cms\Page $page) {
    $textLanguageCode = null;
    $text = $page->text()->toBlocks();

    if ($text->isEmpty()) {
        $textLanguageCode = $kirby
            ->languages()
            ->not($kirby->languageCode())
            ->first()
            ->code();

        $text = $page
            ->content($textLanguageCode)
            ->text()
            ->toBlocks();
    }

    return compact('textLanguageCode', 'text');
};
