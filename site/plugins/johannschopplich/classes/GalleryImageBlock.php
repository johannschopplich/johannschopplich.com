<?php

namespace JohannSchopplich\Website;

use Kirby\Cms\Block;
use Kirby\Cms\File;
use Kirby\Filesystem\Asset;

class GalleryImageBlock extends Block
{
    protected File|Asset|false|null $source = null;

    public function isRemote(): bool
    {
        return $this->location()->value() === 'web';
    }

    public function source(): File|Asset|null
    {
        $this->source ??= ($this->isRemote()
            ? RemoteImage::asset($this->src()->value() ?? '')
            : $this->image()->toFile()) ?? false;

        return $this->source ?: null;
    }

    public function altText(): string
    {
        $alt = $this->isRemote() ? $this->alt() : $this->source()?->alt();
        return $alt?->value() ?? '';
    }
}
