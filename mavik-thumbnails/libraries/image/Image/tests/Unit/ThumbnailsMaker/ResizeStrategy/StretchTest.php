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
use Mavik\Image\ThumbnailsMaker\ResizeStrategy\Stretch;
use Mavik\Image\ThumbnailsMaker\ResizeStrategyInterface;

class StretchTest extends TestCase
{
    public function testOriginalImageArea()
    {
        $strategy = new Stretch();
        $originalSize = new ImageSize(800, 600);
        $thumbnailSize = new ImageSize(200, 300);
        $originalArea = $strategy->originalImageArea($originalSize, $thumbnailSize);
        $this->assertSame(0, $originalArea->x);
        $this->assertSame(0, $originalArea->y);
        $this->assertSame(800, $originalArea->width);
        $this->assertSame(600, $originalArea->height);
    }
    
    public function testRealThumbnailSize()
    {
        $strategy = new Stretch();
        $originalSize = new ImageSize(800, 600);
        $thumbnailSize = new ImageSize(200, 300);
        $realThumbnailSize = $strategy->realThumbnailSize($originalSize, $thumbnailSize);
        $this->assertSame(200, $realThumbnailSize->width);
        $this->assertSame(300, $realThumbnailSize->height);        
    }
}