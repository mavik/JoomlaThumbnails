<?php
/* 
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license MIT; see LICENSE
 */

namespace Mavik\Image\Tests\Unit\GraphicLibrary;

use Mavik\Image\Tests\Unit\GraphicLibrary\AbstractTest;
use Mavik\Image\GraphicLibrary\Gd2;

class Gd2Test extends AbstractTest
{
        
    /**
     * @covers Mavik\Image\GraphicLibrary\Gd2::open
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToOpen
     */
    public function testOpen(string $src, int $imgType)
    {
        parent::testOpen($src, $imgType);
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gd2::save
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToSave
     */
    public function testSave(string $src, int $imgType)
    {
        parent::testSave($src, $imgType);
    }

    /**
     * @covers Mavik\Image\GraphicLibrary\Gd2::getWidth
     * @covers Mavik\Image\GraphicLibrary\Gd2::getHeight
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesSize
     */    
    public function testSize(string $src, int $imgType, int $width, int $height)
    {
        parent::testSize($src, $imgType, $width, $height);
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gd2::clone
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::clone
     */
    public function testClone(string $src, int $imgType)
    {
        parent::testClone($src, $imgType);        
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gd2::crop
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCrop
     */
    public function testCrop(int $imgType, int $x, int $y, int $width, int $height, string $src, string $expectedFile)
    {
        parent::testCrop($imgType, $x, $y, $width, $height, $src, $expectedFile);
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gd2::resize
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToResize
     */
    public function testResize(int $imgType, int $width, int $height, string $src, string $expectedFile)
    {
        parent::testResize($imgType, $width, $height, $src, $expectedFile);
    }

    /**
     * @covers Mavik\Image\GraphicLibrary\Gd2::cropAndResize
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCropAndResize
     */
    public function testCropAndResize(int $imgType, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, string $src, string $expectedFile)
    {
        parent::testCropAndResize($imgType, $x, $y, $width, $height, $toWidth, $toHeight, $src, $expectedFile);
    }

    protected function newInstance(): \Mavik\Image\GraphicLibraryInterface
    {
        return new Gd2();
    }

    protected function verifyResource($resource): void
    {
        if (is_object($resource)) {
            $this->assertInstanceOf(\GdImage::class, $resource);
            return;
        }
        $this->assertIsResource($resource);
    }

}