<?php
declare(strict_types=1);

/**
 * PHP Library for replacing images in html to thumbnails.
 *
 * @package Mavik\Thumbnails
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2023 Vitalii Marenkov
 * @license GNU General Public License version 2 or later; see LICENSE
 */
namespace Mavik\Thumbnails;

use Mavik\Image\ImageFactory;
use Mavik\Image\ImageImmutable;
use Mavik\Image\Configuration as ImageFactoryConfiguration;
use Mavik\Thumbnails\Html\Document;
use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Specification\Image\ReplaceWithThumbnail as ReplaceWithThumbnailSpecification;
use Mavik\Thumbnails\Specification\Image\UseDefaultSize as UseDefaultSizeSpecification;

class Thumbnails
{   
    /** @var ImageFactory */
    private $imageFactory;

    /** @var \SplObjectStorage */
    private $actions;
    
    public function __construct(Configuration $configuration)
    {
        $serverConfiguration = $configuration->server();
        $imageFactoryConfiguration = new ImageFactoryConfiguration(
            $serverConfiguration->baseUrl(),
            $serverConfiguration->webRootDir(),
            $serverConfiguration->thumbnailsDir(),
            $serverConfiguration->graphicLibraryPriority()
        );
        $this->imageFactory = new ImageFactory($imageFactoryConfiguration);
        $this->actions = new \SplObjectStorage();
        $this->addActionDefaultSize($configuration);
        $this->addActionPopUp($configuration);
        $this->addActionReplaceToThumbnail($configuration);        
    }

    private function addActionDefaultSize(Configuration $configuration): void
    {
        $action = new Action\UseDefaultSize($configuration);
        $this->actions[$action] = new UseDefaultSizeSpecification($configuration);
    }
    
    private function addActionReplaceToThumbnail(Configuration $configuration): void
    {
        $action = new Action\ReplaceToThumbnail($configuration);
        $this->actions[$action] = new ReplaceWithThumbnailSpecification($configuration);
    }

    private function addActionPopUp(Configuration $configuration): void
    {
        $popUp = $configuration->base()->popUp();
        if ($popUp === null) {
            return;
        }
        $actionClass = '\\Mavik\\Thumbnails\\Action\\PopUp\\' . $configuration->base()->popUp();
        $action = new $actionClass();
        $this->actions[$action] = new Specification\Image\AddPopUp($configuration);
    }

    /**
     * Replace images in html to thumbnails.
     * 
     * @throws Exception
     */
    public function __invoke(string $html): Result
    {
        $document = Document::createFragment($html, $this->imageFactory);
        $jsAndCss = new JsAndCss();
        foreach ($document->findImages() as $imageTag) {
            $this->doActions($imageTag, $jsAndCss);
        }
        return new Result((string)$document, $jsAndCss);
    }
    
    private function doActions(Image $image, JsAndCss $jsAndCss): void
    {
        while ($this->actions->valid()) {
            $specification = $this->actions->getInfo();
            if ($specification->isSatisfiedBy($image)) {
                $action = $this->actions->current();
                $action($image, $jsAndCss);
            }
            $this->actions->next();
        }

    }
}
