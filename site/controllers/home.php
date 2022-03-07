<?php

return function (\Kirby\Cms\App $kirby, \Kirby\Cms\Page $page) {
    $kirby->response()->cache(false);
};
