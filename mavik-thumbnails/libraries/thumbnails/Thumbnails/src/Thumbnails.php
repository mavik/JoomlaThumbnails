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

class Thumbnails
{
    private const ACTIONS = [
        Action\UseDefaultSize::class,
        Action\ReplaceToThumbnail::class,
        Action\AddPopUp::class,
    ];

    /** @var ImageFactory */
    private $imageFactory;

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
        foreach (self::ACTIONS as $actionClass) {
            $action = new $actionClass();
            if ($action->specification()->isSatisfiedBy($image)) {
                $action->execute($image, $jsAndCss);
            }
        }
    }
}
