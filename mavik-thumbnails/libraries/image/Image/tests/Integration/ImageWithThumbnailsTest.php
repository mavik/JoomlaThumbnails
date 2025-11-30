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
use Mavik\Image\ImageFactory;
use Mavik\Image\Tests\CompareImages;
use Mavik\Image\Configuration;

class ImageWithThumbnailsTest extends TestCase
{
    /** @var ImageFactory */
    private static $imageFactory;

    public static function setUpBeforeClass(): void
    {   
        self::$imageFactory = new ImageFactory(
            new Configuration(
                'http://test.com/',
                __DIR__ . '/../resources',
                'thubmnails',
            )
        );
    }    
    
    public function testCreate()
    {
        $origFile = __DIR__ . '/../resources/images/apple.jpg';
        $thumb1 = __DIR__ . '/../temp/apple-1-fit.jpg';
        $thumb2 = __DIR__ . '/../temp/apple-2-fit.jpg';
        $thumb1Sample = __DIR__ . '/../resources/images/resized/apple-100-200-fill.jpg';
        $thumb2Sample = __DIR__ . '/../resources/images/resized/apple-200-400-fill.jpg';
        $image = self::$imageFactory->createImageWithThumbnails(
            $origFile,
            100,
            200,
            'fill',
            'thubmnails',
            [1,2]
        );
        $this->assertEquals(ImageWithThumbnails::class, get_class($image));
        $this->assertEquals(1200, $image->getWidth());
        $this->assertEquals(1200, $image->getHeight());
        $thumbnails = $image->getThumbnails();
        $this->assertCount(2, $thumbnails);
        $this->assertEquals(100, $thumbnails[1]->getWidth());
        $this->assertEquals(200, $thumbnails[1]->getHeight());
        $this->assertEquals(200, $thumbnails[2]->getWidth());
        $this->assertEquals(400, $thumbnails[2]->getHeight());        
        $thumbnails[1]->save($thumb1);
        $this->assertLessThan(1, CompareImages::distance($thumb1, $thumb1Sample));
        unlink($thumb1);
        $thumbnails[2]->save($thumb2);
        $this->assertLessThan(1, CompareImages::distance($thumb2, $thumb2Sample));
        unlink($thumb2);
    }

}