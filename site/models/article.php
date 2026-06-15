<?php

class ArticlePage extends \Kirby\Cms\Page
{
    public function metadata(): array
    {
        $site = $this->site();
        $description = $this->description()->or($this->text()->toBlocks()->excerpt(140))->value();
        $author = $this->author()->toUser()?->name()->value()
            ?: $this->kirby()->users()->first()?->name()->value()
            ?: $site->title()->value();
        $thumbnail = $this->thumbnail()->toFile()?->resize(1200);
        $published = $this->published()->toDate('yyyy-MM-dd');

        $blogPosting = [
            'headline' => $this->title()->value(),
            'description' => $description,
            'url' => $this->url(),
            'isPartOf' => ['@id' => $this->webPageId()],
            'mainEntityOfPage' => ['@id' => $this->webPageId()],
            'inLanguage' => $this->kirby()->languageCode(),
            'author' => $site->personReference(),
            'publisher' => $site->personReference(),
            'datePublished' => $published,
            'dateModified' => $this->modified('yyyy-MM-dd')
        ];

        if ($thumbnail) {
            $blogPosting['image'] = [
                '@type' => 'ImageObject',
                'url' => $thumbnail->url(),
                'width' => $thumbnail->width(),
                'height' => $thumbnail->height()
            ];
        }

        if ($this->categories()->isNotEmpty()) {
            $blogPosting['keywords'] = $this->categories()->split(',');
        }

        return [
            'description' => $description,
            'opengraph' => [
                'type' => 'article',
                'namespace:article' => [
                    'author' => $author,
                    'published_time' => $published
                ]
            ],
            'jsonld' => [
                'WebPage' => ['breadcrumb' => ['@id' => $this->breadcrumbId()]],
                'BlogPosting' => $blogPosting,
                'BreadcrumbList' => $this->breadcrumbList()
            ]
        ];
    }
}
