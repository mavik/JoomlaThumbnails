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

use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Html\Image;

abstract class AbstractImageSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($candidate): bool
    {
        if ($candidate instanceof Image) {
            return $this->isSatisfiedByImage($candidate);
        }
        throw new \InvalidArgumentException('Expected instance of ' . Image::class);
    }

    abstract protected function isSatisfiedByImage(Image $image): bool;
}