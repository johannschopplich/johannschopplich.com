<?php

class ProjectPage extends \Kirby\Cms\Page
{
    public function metadata(): array
    {
        return [
            'description' => $this->description()->or($this->text()->toBlocks()->excerpt(140))->value()
        ];
    }
}
