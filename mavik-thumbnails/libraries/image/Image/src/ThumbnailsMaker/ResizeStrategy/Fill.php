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

use Mavik\Image\ImageSize;
use Mavik\Image\ThumbnailsMaker\ImageArea;
use Mavik\Image\ThumbnailsMaker\ResizeStrategyInterface;
use Mavik\Image\Exception;

class Fill implements ResizeStrategyInterface
{
    public function name(): string
    {
        return 'fill';
    }

    public function originalImageArea(ImageSize $originalSize, ImageSize $thumbnailSize): ImageArea
    {
        if (empty($thumbnailSize->width) || empty($thumbnailSize->height)) {
            return new ImageArea(0, 0, $originalSize->width, $originalSize->height);
        }        
        if ($originalSize->width/$originalSize->height < $thumbnailSize->width/$thumbnailSize->height) {
                $x = 0;
                $widht = $originalSize->width;
                $height = (int)round($thumbnailSize->height * $widht/$thumbnailSize->width);
                $y = (int)round(($originalSize->height - $height)/2);
        } else {
                $y = 0;
                $height = $originalSize->height;
                $widht = (int)round($thumbnailSize->width * $height/$thumbnailSize->height);
                $x = (int)round(($originalSize->width - $widht)/2);
        }
        return new ImageArea($x, $y, $widht, $height);
    }
    
    public function realThumbnailSize(ImageSize $originalSize, ImageSize $thumbnailSize): ImageSize
    {
        if ($thumbnailSize->width && $thumbnailSize->height) {
            return $thumbnailSize;
        } elseif ($thumbnailSize->width) {
            return new ImageSize(
                $thumbnailSize->width,
                (int)round($originalSize->height * $thumbnailSize->width / $originalSize->width)
            );
        } elseif ($thumbnailSize->height) {
            return new ImageSize(
                (int)round($originalSize->width * $thumbnailSize->height / $originalSize->height),
                $thumbnailSize->height
            );
        }
        throw new Exception('Cannot calculate thumbnail size in ResizeStrategy\Fill::thumbnailSize');
    }
}
