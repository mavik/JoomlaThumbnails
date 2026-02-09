<?php
/*
 * PHP Library for Image processing and creating thumbnails
 *
 * @package Mavik\Image
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2021 Vitalii Marenkov
 * @license MIT; see LICENSE
*/

namespace Mavik\Image;

use PHPUnit\Framework\TestCase;
use Mavik\Image\ImageSize;
use Mavik\Image\ThumbnailsMaker\ResizeStrategy\Area;

class AreaTest extends TestCase
{
    /**
     * @dataProvider originalImageAreas
     */
    public function testOriginalImageArea($origWidth, $origHeight, $thumbWidth, $thumbHeight, $areaX, $areaY, $areaWidth, $areaHeight)
    {
        $strategy = new Area();
        $originalSize = new ImageSize($origWidth, $origHeight);
        $thumbnailSize = new ImageSize($thumbWidth, $thumbHeight);
        $originalArea = $strategy->originalImageArea($originalSize, $thumbnailSize);
        $this->assertSame($areaX, $originalArea->x);
        $this->assertSame($areaY, $originalArea->y);
        $this->assertSame($areaWidth, $originalArea->width);
        $this->assertSame($areaHeight, $originalArea->height);
    }
    
    /**
     * @dataProvider realThumbnailSizes
     */
    public function testRealThumbnailSize($origWidth, $origHeight, $thumbWidth, $thumbHeight, $realThumbWidth, $realThumbHeight)
    {
        $strategy = new Area();
        $originalSize = new ImageSize($origWidth, $origHeight);
        $thumbnailSize = new ImageSize($thumbWidth, $thumbHeight);
        $realThumbnailSize = $strategy->realThumbnailSize($originalSize, $thumbnailSize);
        $this->assertSame($realThumbWidth, $realThumbnailSize->width);
        $this->assertSame($realThumbHeight, $realThumbnailSize->height);        
    }

    public function originalImageAreas()
    {
        return [
            [800, 600, 400, 300, 0, 0, 800, 600],
            [800, 600, 400, 200, 0, 0, 800, 600],
            [800, 600, 300, 300, 0, 0, 800, 600],
            [600, 800, 400, 200, 0, 0, 600, 800],
            [600, 800, 300, 300, 0, 0, 600, 800],
            [800, 600, 400, null, 0, 0, 800, 600],
            [800, 600, null, 300, 0, 0, 800, 600],
            [600, 800, 400, null, 0, 0, 600, 800],
            [600, 800, null, 300, 0, 0, 600, 800],
        ];
    }
    
    public function realThumbnailSizes()
    {
        return [
            [600, 600, 300, 300, 300, 300],
            [600, 600, 600, 300, 424, 424],            
            [800, 600, 400, 300, 400, 300],            
            [800, 600, 400, 150, 283, 212],
            [800, 600, 200, 300, 283, 212],            
            [600, 800, 150, 400, 212, 283],
            [600, 800, 300, 200, 212, 283],
            [800, 600, null, 300, 400, 300],
            [800, 600, 400, null, 400, 300],
            [600, 800, null, 400, 300, 400],
            [600, 800, 300, null, 300, 400],
        ];
    }
}