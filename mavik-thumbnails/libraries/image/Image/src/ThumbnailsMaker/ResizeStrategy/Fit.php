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

class Fit implements ResizeStrategyInterface
{
    public function name(): string
    {
        return 'fit';
    }

    public function originalImageArea(ImageSize $originalSize, ImageSize $thumbnailSize): ImageArea
    {
        return new ImageArea(0, 0, $originalSize->width, $originalSize->height);
    }
    
    public function realThumbnailSize(ImageSize $originalSize, ImageSize $thumbnailSize): ImageSize
    {
        if ($this->usingDimension($originalSize, $thumbnailSize) == 'width') {
            return new ImageSize(
                $thumbnailSize->width,
                (int)round($originalSize->height * $thumbnailSize->width / $originalSize->width)
            );
        } else {
            return new ImageSize(
            (int)round($originalSize->width * $thumbnailSize->height / $originalSize->height),
                $thumbnailSize->height
            );            
        }
    }
    
    /**
     * 
     * @param ImageSize $originalSize
     * @param ImageSize $thumbnailSize
     * @return string width|height
     */
    private function usingDimension(ImageSize $originalSize, ImageSize $thumbnailSize): string
    {
        if (
            empty($thumbnailSize->height) || 
            $thumbnailSize->width && 
            round($originalSize->width/$thumbnailSize->width) >= round($originalSize->height/$thumbnailSize->height)
        ) {
            return 'width';
        } elseif ($thumbnailSize->height) {
            return 'height';
        }
        throw new \Exception('Cannot select dimension in ResizeStrategy\Fit::usingDimension');
    }
}
