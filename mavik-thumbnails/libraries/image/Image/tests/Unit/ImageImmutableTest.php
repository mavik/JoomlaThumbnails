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
use Mavik\Image\Tests\HttpServer;
use Mavik\Image\Tests\CompareImages;

// It's needed for testing with images from Wikipedia
ini_set('user_agent', "UnitTest Bot");

class ImageImmutableTest extends TestCase
{
    /** @var Configuration */
    private static $configuration;

    public static function setUpBeforeClass(): void
    {
        HttpServer::start();
        self::$configuration = new Configuration(
            'http://test.com/',
            __DIR__ . '/../resources',
            'thumbnails',
        );
    }
    
    /**
     * @covers ImageImmutable::getType
     * @dataProvider files
     */
    public function testGetType(string $src, array $info)
    {
        $image = ImageImmutable::create($src, self::$configuration);
        $this->assertEquals($info['type'], $image->getType());
    }

    /**
     * @covers ImageImmutable::getWidth
     * @dataProvider files
     */
    public function testGetWidth(string $src, array $info)
    {
        $image = ImageImmutable::create($src, self::$configuration);
        $this->assertEquals($info['width'], $image->getWidth());
    }
    
    /**
     * @covers ImageImmutable::getHeight
     * @dataProvider files
     */
    public function testGetHeight(string $src, array $info)
    {
        $image = ImageImmutable::create($src, self::$configuration);
        $this->assertEquals($info['height'], $image->getHeight());
    }    
    
    /**
     * @covers ImageImmutable::getFileSize
     * @dataProvider files
     */
    public function testGetFileSize(string $src, array $info)
    {
        $image = ImageImmutable::create($src, self::$configuration);
        $this->assertEquals($info['file_size'], $image->getFileSize());
    }
    
    /**
     * @covers ImageImmutable::save
     * @dataProvider imagesToSave
     */
    public function testSave(string $src)
    {    
        $savedFile = __DIR__ . '/../temp/' . basename($src);
        $image = ImageImmutable::create($src, self::$configuration);
        $image->save($savedFile);                        
        $this->assertLessThan(1, CompareImages::distance($src, $savedFile));
        unlink($savedFile);
    }

    /**
     * @covers ImageImmutable::__clone
     */
    public function testClone()
    {
        $origFile = __DIR__ . '/../resources/images/apple.jpg';
        $savedFile = __DIR__ . '/../temp/' . basename($origFile);

        $image = ImageImmutable::create($origFile, self::$configuration);
        $croppedImage = $image->crop(25, 40, 400, 500);
        $newImage = clone $croppedImage;        
        $croppedImage->crop(50, 50, 50, 50);
        $newImage->save($savedFile);
        
        $this->assertLessThan(1, CompareImages::distance(__DIR__ . '/../resources/images/crop/apple-25-40-400-500.jpg', $savedFile));
        unlink($savedFile);
    }

    /**
     * @covers Mavik\Image\ImageImmutable::crop
     */    
    public function testCrop()
    {
        $origFile = __DIR__ . '/../resources/images/apple.jpg';
        $origSavedFile = __DIR__ . '/../temp/origin-' . basename($origFile);
        $savedFile = __DIR__ . '/../temp/' . basename($origFile);
        
        $image = ImageImmutable::create($origFile, self::$configuration);
        $newImage = $image->crop(25, 40, 400, 500);

        $this->assertEquals(400, $newImage->getWidth());
        $this->assertEquals(500, $newImage->getHeight());
        $this->assertEquals(1200, $image->getWidth());
        $this->assertEquals(1200, $image->getHeight());
        
        $image->save($origSavedFile);        
        $this->assertLessThan(1, CompareImages::distance($origFile, $origSavedFile));
        unlink($origSavedFile);
        
        $newImage->save($savedFile);        
        $this->assertLessThan(1, CompareImages::distance(__DIR__ . '/../resources/images/crop/apple-25-40-400-500.jpg', $savedFile));
        unlink($savedFile);        
    }

    /**
     * @covers Mavik\Image\ImageImmutable::resize
     */    
    public function testResize()
    {
        $origFile = __DIR__ . '/../resources/images/apple.jpg';
        $originSavedFile = __DIR__ . '/../temp/origin-' . basename($origFile);
        $savedFile = __DIR__ . '/../temp/' . basename($origFile);
        
        $image = ImageImmutable::create($origFile, self::$configuration);
        $newImage = $image->resize(400, 500);
    
        $this->assertEquals(1200, $image->getWidth());
        $this->assertEquals(1200, $image->getHeight());
        $this->assertEquals(400, $newImage->getWidth());
        $this->assertEquals(500, $newImage->getHeight());        
    
        $image->save($originSavedFile);        
        $this->assertLessThan(1, CompareImages::distance($originSavedFile, $origFile));
        unlink($originSavedFile);
        
        $newImage->save($savedFile);        
        $this->assertLessThan(1, CompareImages::distance(__DIR__ . '/../resources/images/resized/apple-400-500.jpg', $savedFile));
        unlink($savedFile);        
    }
    
    /**
     * @covers Mavik\Image\ImageImmutable::resize
     */    
    public function testCropAndResize()
    {
        $origFile = __DIR__ . '/../resources/images/apple.jpg';
        $originSavedFile = __DIR__ . '/../temp/origin-' . basename($origFile);
        $savedFile = __DIR__ . '/../temp/' . basename($origFile);
                
        $image = ImageImmutable::create($origFile, self::$configuration);
        $newImage = $image->cropAndResize(25, 40, 400, 500, 200, 200);
        
        $this->assertEquals(1200, $image->getWidth());
        $this->assertEquals(1200, $image->getHeight());

        $this->assertEquals(200, $newImage->getWidth());
        $this->assertEquals(200, $newImage->getHeight());
        
        $image->save($originSavedFile);
        $this->assertLessThan(1, CompareImages::distance($originSavedFile, $origFile));
        unlink($originSavedFile);
        
        $newImage->save($savedFile);        
        $this->assertLessThan(1, CompareImages::distance(__DIR__ . '/../resources/images/crop-and-resize/apple-25-40-400-500-200-200.jpg', $savedFile));
        unlink($savedFile);
    }    
        
    public function imagesToSave()
    {
        return [
            0 => [__DIR__ . '/../resources/images/apple.jpg'],
            1 => [__DIR__ . '/../resources/images/butterfly_with_transparent_bg.png'],
            2 => [__DIR__ . '/../resources/images/snowman-pixel.gif'],
        ];
    }

    public function files()
    {
        return [
            [   0 =>
                __DIR__ . '/../resources/images/apple.jpg',
                [
                    'width'     => 1200,
                    'height'    => 1200,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 224643,
                ]
            ],[ 1 =>
                __DIR__ . '/../resources/images/butterfly_with_transparent_bg.png',
                [
                    'width'     => 1280,
                    'height'    => 1201,
                    'type'      => IMAGETYPE_PNG,
                    'file_size' => 308897,    
                ]            
            ],[ 2 =>
                __DIR__ . '/../resources/images/chrismas tree with transparent bg.png',
                [
                    'width'     => 1615,
                    'height'    => 1920,
                    'type'      => IMAGETYPE_PNG,
                    'file_size' => 141327,    
                ]
            ],[ 3 =>
                __DIR__ . '/../resources/images/pinapple-animated.gif',
                [
                    'width'     => 457,
                    'height'    => 480,
                    'type'      => IMAGETYPE_GIF,
                    'file_size' => 157012,
                ]
            ],[ 4 =>
                __DIR__ . '/../resources/images/snowman-pixel.gif',
                [
                    'width'     => 700,
                    'height'    => 1300,
                    'type'      => IMAGETYPE_GIF,
                    'file_size' => 53777,
                ]
            ],[ 5 =>
               __DIR__ . '/../resources/images/tree_with_white_background.jpg',
                [
                    'width'     => 1280,
                    'height'    => 1280,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 181304,
                ] 
            ],[ 6 =>
                __DIR__ . '/../resources/images/house.webp',
                [
                    'width'     => 1536,
                    'height'    => 1024,
                    'type'      => IMAGETYPE_WEBP,
                    'file_size' => 644986,
                ]
            ],[ 7 =>
                __DIR__ . '/../resources/images/beach.webp',
                [
                    'width'     => 730,
                    'height'    => 352,
                    'type'      => IMAGETYPE_WEBP,
                    'file_size' => 69622,
                ]
            ],[ 8 =>
                'http://localhost:8888/apple.jpg',
                [
                    'width'     => 1200,
                    'height'    => 1200,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 224643,    
                ]
            ],[ 9 =>
                'http://localhost:8888/beach.webp',
                [
                    'width'     => 730,
                    'height'    => 352,
                    'type'      => IMAGETYPE_WEBP,
                    'file_size' => 69622,
                ]
            ],[ 10 =>
                'https://upload.wikimedia.org/wikipedia/en/a/a7/Culinary_fruits_cropped_top_view.jpg',
                [
                    'width'     => 3224,
                    'height'    => 2145,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 2925171,
                ]
            ],[ 11 =>
                'https://pixnio.com/free-images/2020/01/24/2020-01-24-08-50-32-1200x800.jpg',
                [
                    'width'     => 1200,
                    'height'    => 800,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 169395,
                ]
            ]
        ];
    }
}