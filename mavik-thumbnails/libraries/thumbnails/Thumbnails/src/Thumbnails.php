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
use Mavik\Image\Configuration as ImageFactoryConfiguration;
use Mavik\Thumbnails\Html\Document;
use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Action;
use Mavik\Thumbnails\Action\ActionInterface;

class Thumbnails
{
    private const ACTIONS = [
        Action\UseDefaultSize::class,
        Action\ReplaceToThumbnail::class,
        Action\AddPopUp::class,
    ];

    /** @var Configuration */
    private $configuration;

    /** @var ImageFactory */
    private $imageFactory;

    /** @var ActionInterface[] */
    private $actions;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $serverConfiguration = $configuration->server();
        $imageFactoryConfiguration = new ImageFactoryConfiguration(
            $serverConfiguration->baseUrl(),
            $serverConfiguration->webRootDir(),
            $serverConfiguration->thumbnailsDir(),
            $serverConfiguration->graphicLibraryPriority()
        );
        $this->imageFactory = new ImageFactory($imageFactoryConfiguration);
        $this->actions = [];
        foreach (self::ACTIONS as $actionClass) {
            $this->actions[] = new $actionClass($this->configuration);
        }
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
        return new Result((string) $document, $jsAndCss);
    }

    private function doActions(Image $image, JsAndCss $jsAndCss): void
    {
        foreach ($this->actions as $action) {
            if ($action->specification($this->configuration)->isSatisfiedBy($image)) {
                $action->execute($image, $jsAndCss);
            }
        }
    }
}
