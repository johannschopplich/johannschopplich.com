<?php

class ProjectPage extends \Kirby\Cms\Page
{
    public function metadata(): array
    {
        $description = $this->description()->or($this->text()->toBlocks()->excerpt(140))->value();
        $thumbnail = $this->thumbnail()->toFile()?->resize(1200);

        $creativeWork = [
            'name' => $this->title()->value(),
            'description' => $description,
            'url' => $this->url(),
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
                'CreativeWork' => $creativeWork,
                'BreadcrumbList' => $this->breadcrumbList()
            ]
        ];
    }
}
