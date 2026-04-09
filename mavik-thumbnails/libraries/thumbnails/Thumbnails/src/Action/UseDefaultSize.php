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

use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Specification\Image\UseDefaultSize as UseDefaultSizeSpecification;

class UseDefaultSize extends AbstractAction
{
    /**
     * Change $image and add JS and CSS to $jsAndCss.
     */
    public function execute(Image $image, JsAndCss $jsAndCss): void
    {
        $image->setSize(
            $this->configuration->base()->defaultWidth(),
            $this->configuration->base()->defaultHeight()
        );
    }

    protected function createSpecification(): AbstractSpecification
    {
        return new UseDefaultSizeSpecification($this->configuration);
    }
}