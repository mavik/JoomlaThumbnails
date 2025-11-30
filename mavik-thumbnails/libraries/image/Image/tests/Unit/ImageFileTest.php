<?php
namespace Mavik\Image;

use PHPUnit\Framework\TestCase;
use Mavik\Image\Tests\HttpServer;

class ImageFileTest extends TestCase
{   
    public static function setUpBeforeClass(): void
    {
        HttpServer::start();
    }
    
    /**
     * @covers ImageFile::getFileSize
     * @dataProvider correctImagesProvider
     */
    public function testGetFileSize(string $url, string $path = null, array $trueResult = [])
    {        
        $file = new ImageFile($this->fileName($path, $url));
        $fileSize = $file->getFileSize();
        $this->assertEquals($trueResult['file_size'], $fileSize);
    }
    
    /**
     * @covers ImageFile::getImageSize
     * @dataProvider correctImagesProvider
     */
    public function testGetImageSize(string $url, string $path = null, array $trueResult = [])
    {        
        $file = new ImageFile($this->fileName($path, $url));
        $imageSize = $file->getImageSize();
        $this->assertEquals($trueResult['width'], $imageSize->width);
        $this->assertEquals($trueResult['height'], $imageSize->height);
    }
    
    /**
     * @covers ImageFile::getType
     * @dataProvider correctImagesProvider
     */
    public function testGetType(string $url, string $path = null, array $trueResult = [])
    {        
        $file = new ImageFile($this->fileName($path, $url));
        $type = $file->getType();
        $this->assertEquals($trueResult['type'], $type);
    }
    
    /**
     * @covers ImageFile::getFileSize
     * @dataProvider wrongImagesProvider
     */    
    public function testImageFileGetFileSize_WrongImages(string $url, string $path = null, string $messageRegExp)
    {
        $this->expectExceptionMessageMatches($messageRegExp);        
        $file = new ImageFile($this->fileName($path, $url));
        $file->getFileSize();
    }

    protected function fileName($path, $url): FileName
    {
        $fileName = $this->createMock(FileName::class);
        $fileName->method('getPath')->willReturn($path);
        $fileName->method('getUrl')->willReturn($url);
        return $fileName;
    }    
    
    public function correctImagesProvider()
    {
        return [
            0 => [
                'http://localhost:8888/images/apple.jpg',
                __DIR__ . '/../resources/images/apple.jpg',
                [
                    'width'     => 1200,
                    'height'    => 1200,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 224643,
                ]
            ],
            1 => [
                'http://localhost:8888/images/butterfly_with_transparent_bg.png',
                __DIR__ . '/../resources/images/butterfly_with_transparent_bg.png',
                [
                    'width'     => 1280,
                    'height'    => 1201,
                    'type'      => IMAGETYPE_PNG,
                    'file_size' => 308897,    
                ]
            ],
            2 => [
                'http://localhost:8888/images/chrismas tree with transparent bg.png',
                __DIR__ . '/../resources/images/chrismas tree with transparent bg.png',
                [
                    'width'     => 1615,
                    'height'    => 1920,
                    'type'      => IMAGETYPE_PNG,
                    'file_size' => 141327,    
                ]
            ],
            3 => [
                'http://localhost:8888/images/pinapple-animated.gif',
                __DIR__ . '/../resources/images/pinapple-animated.gif',
                [
                    'width'     => 457,
                    'height'    => 480,
                    'type'      => IMAGETYPE_GIF,
                    'file_size' => 157012,
                ]
            ],
            4 => [
                'http://localhost:8888/images/snowman-pixel.gif',
                __DIR__ . '/../resources/images/snowman-pixel.gif',
                [
                    'width'     => 700,
                    'height'    => 1300,
                    'type'      => IMAGETYPE_GIF,
                    'file_size' => 53777,
                ]
            ],
            5 => [
                'http://localhost:8888/images/tree_with_white_background.jpg',
                __DIR__ . '/../resources/images/tree_with_white_background.jpg',
                [
                    'width'     => 1280,
                    'height'    => 1280,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 181304,
                ]
            ],
            6 => [
                'http://localhost:8888/images/house.webp',
                __DIR__ . '/../resources/images/house.webp',
                [
                    'width'     => 1536,
                    'height'    => 1024,
                    'type'      => IMAGETYPE_WEBP,
                    'file_size' => 644986,
                ]
            ],
            7 => [
                'http://localhost:8888/images/beach.webp',
                __DIR__ . '/../resources/images/beach.webp',
                [
                    'width'     => 730,
                    'height'    => 352,
                    'type'      => IMAGETYPE_WEBP,
                    'file_size' => 69622,
                ]
            ],
            8 => [
                'http://localhost:8888/apple.jpg',
                null,
                [
                    'width'     => 1200,
                    'height'    => 1200,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 224643,    
                ]
            ],
            9 => [
                'http://localhost:8888/beach.webp',
                null,
                [
                    'width'     => 730,
                    'height'    => 352,
                    'type'      => IMAGETYPE_WEBP,
                    'file_size' => 69622,
                ]
            ],
            10 => [
                'https://upload.wikimedia.org/wikipedia/en/a/a7/Culinary_fruits_cropped_top_view.jpg',
                null,
                [
                    'width'     => 3224,
                    'height'    => 2145,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 2925171,
                ]
            ],
            11 => [
                'https://pixnio.com/free-images/2020/01/24/2020-01-24-08-50-32-1200x800.jpg',
                null,
                [
                    'width'     => 1200,
                    'height'    => 800,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 169395,
                ]
            ],
            12 => [
                'http://localhost:8888/apple.php',
                null,
                [
                    'width'     => 1200,
                    'height'    => 1200,
                    'type'      => IMAGETYPE_JPEG,
                    'file_size' => 224643,    
                ]
            ],
            
        ];        
    }
    
    public function wrongImagesProvider()
    {
        return [
            'http://localhost:8888/404.jpg' => [
                'http://localhost:8888/404.jpg',
                null,
                '/^Can\'t open URL/',
            ],
            'http://localhost:8888/not_image.jpg' => [
                'http://localhost:8888/not_image.jpg',
                null,
                '/^Can\'t get size or type of image/',
            ],            
        ];
    }
}