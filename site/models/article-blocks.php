<?php

class ArticleBlocksPage extends \Kirby\Cms\Page
{
    public function metadata(): array
    {
        $description = $this->description()->or($this->text()->excerpt(140))->value();
        $author = $this->author()->toUser() ? $this->author()->toUser()->name()->value() : site()->title()->value();
        $thumbnail = $this->thumbnail()->toFile() ? $this->thumbnail()->toFile()->resize(1200) : null;

        return [
            'description' => $description,
            'opengraph' => [
                'type' => 'article',
                'namespace:article' => [
                    'author' => $author,
                    'published_time' => $this->published()->toDate('%Y-%m-%d')
                ]
            ],
            'jsonld' => [
                'BlogPosting' => [
                    'headline' => $this->title()->value(),
                    'description' => $description,
                    'url' => $this->url(),
                    'image' => $thumbnail !== null ? [
                        '@type' => 'ImageObject',
                        'url' => $thumbnail->url(),
                        'width' => $thumbnail->width(),
                        'height' => $thumbnail->height()
                    ] : null,
                    'author' => [
                        '@type' => 'Person',
                        'name' => $author,
                        'url' => url()
                    ],
                    'datePublished' => $this->published()->toDate('%Y-%m-%d'),
                    'dateModified' => $this->modified('%Y-%m-%d')
                ]
            ]
        ];
    }
}
