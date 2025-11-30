<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */
namespace Mavik\Image\ThumbnailsMaker\ResizeStrategy;

use Mavik\Image\ThumbnailsMaker\ResizeStrategyInterface;
use Mavik\Image\ImageSize;
use Mavik\Image\ThumbnailsMaker\ImageArea;

class Stretch implements ResizeStrategyInterface
{
    public function name(): string
    {
        return 'stretch';
    }

    public function originalImageArea(ImageSize $originalSize, ImageSize $thumbnailSize): ImageArea
    {
        return new ImageArea(0, 0, $originalSize->width, $originalSize->height);
    }
    
    public function realThumbnailSize(ImageSize $originalSize, ImageSize $thumbnailSize): ImageSize
    {
        return $thumbnailSize;
    }
}
