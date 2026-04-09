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

use Mavik\Thumbnails\Html\Image as ImageTag;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Specification\Image\AddPopUp as AddPopUpSpecification;

class AddPopUp extends AbstractAction
{
    /** @var ActionInterface */
    private $library;

    /**
     * Change $imageTag and add JS and CSS to $jsAndCss.
     */
    public function execute(ImageTag $imageTag, JsAndCss $jsAndCss): void
    {
        $popUp = $this->configuration->base()->popUp();
        if ($popUp && empty($this->library)) {
            $libraryName = __NAMESPACE__ . '\\PopUp\\' . $this->configuration->base()->popUp();
            $this->library = new $libraryName($this->configuration);
        }
        $this->library?->execute($imageTag, $jsAndCss);
    }

    protected function createSpecification(): AbstractSpecification
    {
        return new AddPopUpSpecification($this->configuration);
    }
}