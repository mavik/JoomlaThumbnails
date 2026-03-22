<?php
declare(strict_types=1);

/**
 * PHP Library for replacing images in html to thumbnails.
 *
 * @package Mavik\Thumbnails
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2023 Vitalii Marenkov
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Mavik\Thumbnails\Action;

use Mavik\Image\Image;
use Mavik\Thumbnails\Html\Image as ImageTag;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Specification\Image\AddPopUp as AddPopUpSpecification;

class AddPopUp implements ActionInterface
{
    /** @var AbstractSpecification */
    private $specification;

    /** @var ActionInterface */
    private $library;

    public function __construct(Configuration $configuration)
    {
        $this->specification = new AddPopUpSpecification($configuration);
        $popUp = $configuration->base()->popUp();
        if ($popUp) {
            $libraryName = 'PopUp\\' . $configuration->base()->popUp();
            $this->library = new $libraryName($configuration);
        }
    }

    /**
     * Change $imageTag and add JS and CSS to $jsAndCss.
     */
    public function execute(ImageTag $imageTag, JsAndCss $jsAndCss): void
    {
        $this->library?->execute($imageTag, $jsAndCss);
    }

    public function specification(): AbstractSpecification
    {
        return $this->specification;
    }
}