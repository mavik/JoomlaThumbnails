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

use PHPUnit\Framework\TestCase;
use Mavik\Image\Tests\HttpServer;
use Mavik\Image\Tests\CompareImages;
use Mavik\Image\GraphicLibraryInterface;
use Mavik\Image\ImageFile;

ini_set('user_agent', "UnitTest Bot");

abstract class AbstractTest extends TestCase
{
    /** @var GraphicLibraryInterface */
    protected $instance;
    
    public static function setUpBeforeClass(): void
    {
        HttpServer::start();
    }
    
    public function setUp(): void
    {
        $this->instance = $this->newInstance();
    }

    public function testOpen(string $src, int $imgType)
    {        
        $resource = $this->instance->load($this->imageFile($src, $imgType));
        $this->verifyResource($resource);
    }
    
    public function testSave(string $src, int $imgType)
    {
        $savedFile = __DIR__ . '/../../temp/' . basename($src);
        $resource = $this->instance->load($this->imageFile($src, $imgType));
        $this->instance->save($resource, $savedFile, $imgType);
        $this->assertLessThan(1, CompareImages::distance($savedFile, $src));
        unlink($savedFile);
    }
    
    public function testSize(string $src, int $imgType, int $width, int $height)
    {
        $resource = $this->instance->load($this->imageFile($src, $imgType));
        $this->assertEquals($width, $this->instance->getWidth($resource));
        $this->assertEquals($height, $this->instance->getHeight($resource));
    }

    public function testClone(string $src, int $imgType)
    {
        $resource = $this->instance->load($this->imageFile($src, $imgType));
        $newResource = $this->instance->clone($resource);
        $this->instance->crop($resource, 50, 50, 50, 50);
        $savedFile = __DIR__ . '/../../temp/' . basename($src);
        $this->instance->save($newResource, $savedFile, $imgType);
        $this->assertLessThan(1, CompareImages::distance($src, $savedFile));
        unlink($savedFile);
    }


    public function testCrop(int $imgType, int $x, int $y, int $width, int $height, string $src, string $expectedFile)
    {
        $savedFile = __DIR__ . '/../../temp/' . basename($src);
        
        $image = $this->instance->load($this->imageFile($src, $imgType));
        $cropedImage = $this->instance->crop($image, $x, $y, $width, $height);
        $this->instance->save($cropedImage, $savedFile, $imgType);
        
        $imageSize = getimagesize($savedFile);
        $this->assertEquals($width, $imageSize[0]);
        $this->assertEquals($height, $imageSize[1]);
        $this->assertEquals($imgType, $imageSize[2]);
        
        $this->assertLessThan(1, CompareImages::distance($expectedFile, $savedFile));
        unlink($savedFile);
    }
    
    public function testResize(int $imgType, int $width, int $height, string $src, string $expectedFile)
    {
        $savedFile = __DIR__ . '/../../temp/' . basename($src);

        $image = $this->instance->load($this->imageFile($src, $imgType));
        $resizedImage = $this->instance->resize($image, $width, $height);
        $this->instance->save($resizedImage, $savedFile, $imgType);
        
        $imageSize = getimagesize($savedFile);
        $this->assertEquals($width, $imageSize[0]);
        $this->assertEquals($height, $imageSize[1]);
        $this->assertEquals($imgType, $imageSize[2]);
        
        $this->assertLessThan(3, CompareImages::distance($expectedFile, $savedFile));
        unlink($savedFile);
    }    

    public function testCropAndResize(int $imgType, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, string $src, string $expectedFile)
    {
        $savedFile = __DIR__ . '/../../temp/' . basename($src);
        
        $image = $this->instance->load($this->imageFile($src, $imgType));
        $cropedImage = $this->instance->cropAndResize($image, $x, $y, $width, $height, $toWidth, $toHeight);
        $this->instance->save($cropedImage, $savedFile, $imgType);
        
        $imageSize = getimagesize($savedFile);
        $this->assertEquals($toWidth, $imageSize[0]);
        $this->assertEquals($toHeight, $imageSize[1]);
        $this->assertEquals($imgType, $imageSize[2]);
        
        $this->assertLessThan(3, CompareImages::distance($expectedFile, $savedFile));
        unlink($savedFile);
    }
    
    public function testImmutable()
    {
        $imagePath = __DIR__ . '/../../resources/images/apple.jpg';
        $tempImagePath = __DIR__ . '/../../temp/apple.jpg';
        $imageSize = getimagesize($imagePath);        
        $image = $this->instance->load($this->imageFile($imagePath, IMAGETYPE_JPEG));
        
        $this->instance->crop($image, 50, 50, 300, 300, true);
        $this->assertNotEmpty($image);
        $this->instance->save($image, $tempImagePath, IMAGETYPE_JPEG);
        $tempImageSize = getimagesize($tempImagePath);
        $this->assertEquals($imageSize, $tempImageSize);
        unlink($tempImagePath);
        
        $this->instance->resize($image, 300, 300, true);
        $this->assertNotEmpty($image);
        $this->instance->save($image, $tempImagePath, IMAGETYPE_JPEG);
        $tempImageSize = getimagesize($tempImagePath);
        $this->assertEquals($imageSize, $tempImageSize);
        unlink($tempImagePath);
        
        $this->instance->cropAndResize($image, 50, 50, 600, 600, 300, 300, true);
        $this->assertNotEmpty($image);
        $this->instance->save($image, $tempImagePath, IMAGETYPE_JPEG);
        $tempImageSize = getimagesize($tempImagePath);
        $this->assertEquals($imageSize, $tempImageSize);
        unlink($tempImagePath);
    }
    
    public function testMutable()
    {
        $imagePath = __DIR__ . '/../../resources/images/apple.jpg';
        $imageFile = $this->imageFile($imagePath, IMAGETYPE_JPEG);
        
        $image = $this->instance->load($imageFile);        
        $processedImage = $this->instance->crop($image, 50, 50, 300, 300);
        $this->assertEquals($processedImage, $image);
        
        $image = $this->instance->load($imageFile);        
        $processedImage = $this->instance->resize($image, 300, 300);
        $this->assertEquals($processedImage, $image);
        
        $image = $this->instance->load($imageFile);        
        $processedImage = $this->instance->cropAndResize($image, 50, 50, 600, 600, 300, 300);
        $this->assertEquals($processedImage, $image);
    }    
    
    abstract protected function newInstance(): GraphicLibraryInterface;
    
    /**
     * @param mix $resource Type depends on using Graphic Library
     */
    abstract protected function verifyResource($resource): void;
    
    protected function imageFile($src, $type): ImageFile
    {
        $imageFile = $this->createMock(ImageFile::class);
        if (strpos($src, 'http') === 0) {
            $imageFile->method('geturl')->willReturn($src);
        } else {
            $imageFile->method('getPath')->willReturn($src);
        }
        $imageFile->method('getType')->willReturn($type);
        return $imageFile;
    }
}