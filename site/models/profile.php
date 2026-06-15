<?php

class ProfilePage extends \Kirby\Cms\Page
{
    public function metadata(): array
    {
        return [
            'jsonld' => [
                'WebPage' => [
                    '@type' => 'ProfilePage',
                    'mainEntity' => $this->site()->personReference(),
                    'dateModified' => $this->modified('yyyy-MM-dd')
                ]
            ]
        ];
    }
}
