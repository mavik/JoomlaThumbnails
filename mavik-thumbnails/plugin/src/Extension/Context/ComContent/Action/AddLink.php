<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context\ComContent\Action;

use Mavik\Thumbnails\Action\ActionInterface;
use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\Html\ImageWithLink;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Configuration;
use Mavik\Plugin\Content\Thumbnails\Extension\Context\ComContent\Specification\Always;

class AddLink implements ActionInterface
{
    public function __construct(
        private Configuration $configuration,
        private string $href
    ) {
    }

    public function execute(Image $image, JsAndCss $jsAndCss): void
    {
        ImageWithLink::createFromImage($image, $this->href);
    }

    public function specification(): AbstractSpecification
    {
        return new Always($this->configuration);
    }
}