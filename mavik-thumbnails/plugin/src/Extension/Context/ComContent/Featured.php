<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context\ComContent;

use Mavik\Plugin\Content\Thumbnails\Extension\Context\BaseContext;
use Mavik\Plugin\Content\Thumbnails\Extension\Context\ComContent\Action\AddLink;

class Featured extends BaseContext
{
    public function getActions(): array|null
    {
        return [
            new AddLink($this->configuration),
        ];
    }
}