<?php

class ProjectPage extends \Kirby\Cms\Page
{
    public function galleryImages(): \Kirby\Cms\Files
    {
        $languageCode = $this->kirby()->languageCode();

        return $this->gallery()->toFiles()->filter(function (\Kirby\Cms\File $image) use ($languageCode) {
            $language = $image->gallery()->toObject()->language();
            return $language->isEmpty() || $language->value() === $languageCode;
        });
    }

    public function metadata(): array
    {
        $description = $this->description()->or($this->text()->toExcerpt(140))->value();
        $thumbnail = $this->thumbnail()->toFile()?->resize(1200);

        $creativeWork = [
            'name' => $this->title()->value(),
            'description' => $description,
            'url' => $this->url(),
            'isPartOf' => ['@id' => $this->webPageId()],
            'mainEntityOfPage' => ['@id' => $this->webPageId()],
            'inLanguage' => $this->kirby()->languageCode(),
            'author' => $this->site()->personReference()
        ];

        if ($thumbnail) {
            $creativeWork['image'] = [
                '@type' => 'ImageObject',
                'url' => $thumbnail->url(),
                'width' => $thumbnail->width(),
                'height' => $thumbnail->height()
            ];
        }

        return [
            'description' => $description,
            'jsonld' => [
                'WebPage' => ['breadcrumb' => ['@id' => $this->breadcrumbId()]],
                'CreativeWork' => $creativeWork,
                'BreadcrumbList' => $this->breadcrumbList()
            ]
        ];
    }
}
