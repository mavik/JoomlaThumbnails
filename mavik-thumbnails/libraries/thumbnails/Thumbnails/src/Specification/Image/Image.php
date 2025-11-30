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

use Mavik\Thumbnails\Specification\Specification;
use Mavik\Thumbnails\Html\Image as HtmlImage;

abstract class Image extends Specification
{
    public function isSatisfiedBy($candidate): bool
    {
        if ($candidate instanceof HtmlImage) {
            return $this->isSatisfiedByImage($candidate);
        }
        throw new \InvalidArgumentException('Expected instance of ' . HtmlImage::class);
    }
    
    abstract protected function isSatisfiedByImage(HtmlImage $image): bool;
}