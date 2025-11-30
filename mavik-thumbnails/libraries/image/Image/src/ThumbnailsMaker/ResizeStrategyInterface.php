<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2022 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */
namespace Mavik\Image\ThumbnailsMaker;

use Mavik\Image\ThumbnailsMaker\ImageArea;
use Mavik\Image\ImageSize;

interface ResizeStrategyInterface 
{
    public function name(): string;

    /** Choose area of original image that will be used */
    public function originalImageArea(ImageSize $originalSize, ImageSize $thumbnailSize): ImageArea; 
    
    /** Define size of thumbnail */
    public function realThumbnailSize(ImageSize $originalSize, ImageSize $thumbnailSize): ImageSize;
}
