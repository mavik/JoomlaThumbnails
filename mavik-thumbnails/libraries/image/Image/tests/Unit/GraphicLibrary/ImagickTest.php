<?php
declare(strict_types=1);

/* 
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license MIT; see LICENSE
 */

namespace Mavik\Image\Tests\Unit\GraphicLibrary;

use Mavik\Image\GraphicLibrary\Imagick;
use Mavik\Image\GraphicLibraryInterface;
use Imagick as NativeImagick;

/**
 * @runTestsInSeparateProcess
 * @preserveGlobalState disabled
 */
class ImagickTest extends AbstractTest
{
    public static function setUpBeforeClass(): void
    {
        if (!extension_loaded('imagick')) {
            $prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
            $isExtensionLoaded = @dl($prefix . 'imagick.' . PHP_SHLIB_SUFFIX);
            if (!$isExtensionLoaded) {
                self::markTestSkipped('Extension imagick is not loaded');
            }
        }
        parent::setUpBeforeClass();
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Imagick::load
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToLoad
     */
    public function testLoad(string $src, int $imgType): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Imagick');
        }
        parent::testLoad($src, $imgType);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Imagick::save
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToSave
     */
    public function testSave(string $src, int $imgType): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Imagick');
        }
        parent::testSave($src, $imgType);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Imagick::getWidth
     * @covers \Mavik\Image\GraphicLibrary\Imagick::getHeight
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesSize
     */
    public function testSize(string $src, int $imgType, int $width, int $height): void
    {
        parent::testSize($src, $imgType, $width, $height);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Imagick::clone
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToClone
     */
    public function testClone(string $src, int $imgType): void
    {
        parent::testClone($src, $imgType);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Imagick::crop
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCrop
     */
    public function testCrop(int $imgType, int $x, int $y, int $width, int $height, string $src, string $expectedFile): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Imagick');
        }
        parent::testCrop($imgType, $x, $y, $width, $height, $src, $expectedFile);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Imagick::resize
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToResize
     */
    public function testResize(int $imgType, int $width, int $height, string $src, string $expectedFile): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Imagick');
        }
        parent::testResize($imgType, $width, $height, $src, $expectedFile);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Imagick::cropAndResize
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCropAndResize
     */
    public function testCropAndResize(int $imgType, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, string $src, string $expectedFile): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Imagick');
        }
        parent::testCropAndResize($imgType, $x, $y, $width, $height, $toWidth, $toHeight, $src, $expectedFile);
    }

    protected function newInstance(): GraphicLibraryInterface
    {
        return new Imagick();
    }

    protected function verifyResource($resource): void
    {
        $this->assertInstanceOf(NativeImagick::class, $resource);
    }

    private function isTypeSupported(int $type): bool
    {
        return
            $type !== IMAGETYPE_WEBP ||
            in_array('WEBP', NativeImagick::queryFormats(), true)
        ;
    }
}