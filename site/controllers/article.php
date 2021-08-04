<?php

return function (\Kirby\Cms\App $kirby, \Kirby\Cms\Page $page) {
    $textLanguageCode = null;
    $text = $page->text();

    if ($text->isEmpty()) {
        $textLanguageCode = $kirby
            ->languages()
            ->not($kirby->languageCode())
            ->first()
            ->code();

        $text = $page
            ->content($textLanguageCode)
            ->text();
    }

    $text = $text->kt()->anchorHeadlines();

    return compact('textLanguageCode', 'text');
};
