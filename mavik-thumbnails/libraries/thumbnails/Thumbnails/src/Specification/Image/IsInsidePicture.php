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

use Mavik\Thumbnails\Html\Image;

class IsInsidePicture extends AbstractImageSpecification
{
    protected function isSatisfiedByImage(Image $image): bool
    {
        $parentElement = $image->getParentElement();
        return $parentElement?->tagName === 'picture';
    }
}
