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

use Mavik\Thumbnails\Html\Image as HtmlImage;

class IsInsideLink extends Image
{
    protected function isSatisfiedByImage(HtmlImage $image): bool
    {
        $currentNode = $image->getParentNode();
        if (empty($currentNode)) {
            return false;
        }
        while ($currentNode = $currentNode->parentNode) {
            if (
                $currentNode instanceof \DOMElement
                && $currentNode->nodeName == 'a'
                && $currentNode->getAttribute('href') !== ''
            ) {
                return true;
            }
        }
        return false;
    }
}
