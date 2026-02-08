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

use Mavik\Image\GraphicLibrary\Gd2;
use Mavik\Image\GraphicLibraryInterface;
use Mavik\Image\Exception\GraphicLibraryException;
use Mavik\Image\ImageFile;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    private GraphicLibraryInterface $instance;

    protected function setUp(): void
    {
        $this->instance = new Gd2();
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gd2::load
     */
    public function testLoadNonExistentFile(): void
    {
        $imageFile = $this->createMock(ImageFile::class);
        $imageFile->method('getPath')->willReturn('/non/existent/file.jpg');
        $imageFile->method('getType')->willReturn(IMAGETYPE_JPEG);

        $this->expectException(GraphicLibraryException::class);
        $this->instance->load($imageFile);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gd2::load
     */
    public function testLoadEmptyPath(): void
    {
        $imageFile = $this->createMock(ImageFile::class);
        $imageFile->method('getPath')->willReturn('');
        $imageFile->method('getUrl')->willReturn('');
        $imageFile->method('getType')->willReturn(IMAGETYPE_JPEG);

        // PHP 8 throws ValueError for empty path in imagecreatefromjpeg
        $this->expectException(\ValueError::class);
        $this->instance->load($imageFile);
    }

    /**
     * @covers \Mavik\Image\GraphicLibrary\Gd2::save
     */
    public function testSaveToInvalidPath(): void
    {
        $imageFile = $this->createMock(ImageFile::class);
        $imageFile->method('getPath')->willReturn(__DIR__ . '/../../resources/images/apple.jpg');
        $imageFile->method('getType')->willReturn(IMAGETYPE_JPEG);

        $resource = $this->instance->load($imageFile);

        $this->expectException(GraphicLibraryException::class);
        // Saving to a directory that doesn't exist
        @$this->instance->save($resource, '/invalid/path/test.jpg', IMAGETYPE_JPEG);
    }
}
