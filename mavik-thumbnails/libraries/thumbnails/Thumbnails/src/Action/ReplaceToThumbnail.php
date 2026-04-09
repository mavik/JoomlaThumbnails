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

namespace Mavik\Thumbnails\Action;

use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Specification\Image\ReplaceWithThumbnail as ReplaceWithThumbnailSpecification;

class ReplaceToThumbnail extends AbstractAction
{
    public function execute(Image $image, JsAndCss $jsAndCss): void
    {
        $image->useThumbnail(
            $this->configuration->base()->resizeType(),
            $this->configuration->base()->scales()
        );
    }

    protected function createSpecification(): AbstractSpecification
    {
        return new ReplaceWithThumbnailSpecification($this->configuration);
    }
}
