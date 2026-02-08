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

use Mavik\Image\GraphicLibrary\Gmagick;
use Mavik\Image\GraphicLibraryInterface;
use Gmagick as NativeGmagick;

class GmagickTest extends AbstractTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    public function setUp(): void
    {
        if (!extension_loaded('gmagick')) {
            $prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
            if (!dl($prefix . 'gmagick.' . PHP_SHLIB_SUFFIX)) {
                $this->markTestSkipped('Extension gmagick is not loaded');
            }
        }
        parent::setUp();
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::load
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToLoad
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testLoad(string $src, int $imgType): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Gmagick');
        }
        parent::testLoad($src, $imgType);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::save
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToSave
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSave(string $src, int $imgType): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Gmagick');
        }
        parent::testSave($src, $imgType);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::getWidth
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::getHeight
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesSize
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSize(string $src, int $imgType, int $width, int $height): void
    {
        parent::testSize($src, $imgType, $width, $height);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::clone
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToClone
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testClone(string $src, int $imgType): void
    {
        parent::testClone($src, $imgType);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::crop
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCrop
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCrop(int $imgType, int $x, int $y, int $width, int $height, string $src, string $expectedFile): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Gmagick');
        }
        parent::testCrop($imgType, $x, $y, $width, $height, $src, $expectedFile);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::resize
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToResize
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testResize(int $imgType, int $width, int $height, string $src, string $expectedFile): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Gmagick');
        }
        parent::testResize($imgType, $width, $height, $src, $expectedFile);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gmagick::cropAndResize
     * @dataProvider \Mavik\Image\Tests\Unit\GraphicLibrary\DataProvider::imagesToCropAndResize
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCropAndResize(int $imgType, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, string $src, string $expectedFile): void
    {
        if (!$this->isTypeSupported($imgType)) {
            $this->markTestSkipped('Image type not supported by Gmagick');
        }
        parent::testCropAndResize($imgType, $x, $y, $width, $height, $toWidth, $toHeight, $src, $expectedFile);
    }

    protected function newInstance(): GraphicLibraryInterface
    {
        return new Gmagick();
    }

    protected function verifyResource($resource): void
    {
        $this->assertInstanceOf(NativeGmagick::class, $resource);
    }

    private function isTypeSupported(int $type): bool
    {
        return
            $type !== IMAGETYPE_WEBP ||
            in_array('WEBP', (new NativeGmagick())->queryFormats(), true)
        ;
    }
}