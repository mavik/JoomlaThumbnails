<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Thumbnails
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2024 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */

 namespace Mavik\Thumbnails\Specification\Image;

use Mavik\Thumbnails\Html;

class IsZoomedOut extends Image
{
    protected function isSatisfiedByImage (Html\Image $image): bool
    {
        return
            $image->isSizeInPixels()
            && (
                $image->getHeight() !== null && $image->getHeight() < $image->getRealHeight()
                || $image->getWidth() !== null && $image->getWidth() < $image->getRealWidth()
            )
        ;
    }
}
