<?php

namespace JohannSchopplich\Website;

class GalleryImageBlock extends ImageBlock
{
    public function altText(): string
    {
        $alt = $this->isRemote() ? $this->alt() : $this->source()?->alt();
        return $alt?->value() ?? '';
    }
}
