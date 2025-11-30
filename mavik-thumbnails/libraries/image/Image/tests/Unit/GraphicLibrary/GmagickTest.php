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
use Mavik\Image\GraphicLibrary\Gmagick;

class GmagickTest extends AbstractTest
{
    
    public static function setUpBeforeClass(): void
    {
        if (!extension_loaded('gmagick')) {
            $prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
            $isExtensionLoaded = dl($prefix . 'gmagick.' . PHP_SHLIB_SUFFIX);
            if (!$isExtensionLoaded) {
                self::markTestSkipped('Extension gmagick is not loaded');
            }
        }               
        parent::setUpBeforeClass();
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gmagick::open
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToOpen
     */
    public function testOpen(string $src, int $imgType)
    {
        if (!$this->isTypeSpported($imgType)) {
            $this->markTestSkipped();
            return;
        }        
        parent::testOpen($src, $imgType);
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gmagick::save
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToSave
     */
    public function testSave(string $src, int $imgType)
    {
        if (!$this->isTypeSpported($imgType)) {
            $this->markTestSkipped();
            return;
        }
        parent::testSave($src, $imgType);
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gmagick::getWidth
     * @covers Mavik\Image\GraphicLibrary\Gmagick::getHeight
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesSize
     */    
    public function testSize(string $src, int $imgType, int $width, int $height)
    {
        parent::testSize($src, $imgType, $width, $height);
    }

    /**
     * @covers Mavik\Image\GraphicLibrary\Gmagick::clone
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::clone
     */
    public function testClone(string $src, int $imgType)
    {
        parent::testClone($src, $imgType);        
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gmagick::crop
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCrop
     */
    public function testCrop(int $imgType, int $x, int $y, int $width, int $height, string $src, string $expectedFile)
    {
        if (!$this->isTypeSpported($imgType)) {
            $this->markTestSkipped();
            return;
        }
        parent::testCrop($imgType, $x, $y, $width, $height, $src, $expectedFile);
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gmagick::resize
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToResize
     */
    public function testResize(int $imgType, int $width, int $height, string $src, string $expectedFile)
    {
        if (!$this->isTypeSpported($imgType)) {
            $this->markTestSkipped();
            return;
        }
        parent::testResize($imgType, $width, $height, $src, $expectedFile);
    }
    
    /**
     * @covers Mavik\Image\GraphicLibrary\Gmagick::cropAndResize
     * @dataProvider Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCropAndResize
     */
    public function testCropAndResize(int $imgType, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, string $src, string $expectedFile)
    {
        if (!$this->isTypeSpported($imgType)) {
            $this->markTestSkipped();
            return;
        }
        parent::testCropAndResize($imgType, $x, $y, $width, $height, $toWidth, $toHeight, $src, $expectedFile);
    }    
    
    protected function newInstance(): Gmagick
    {
        return new Gmagick();
    }

    protected function verifyResource($resource): void
    {
        $this->assertInstanceOf('Gmagick', $resource);
    }

    private function isTypeSpported(int $type): bool {
        return 
            $type != IMAGETYPE_WEBP ||
            in_array('WEBP', (new \Gmagick())->queryFormats())
        ;
    }
}