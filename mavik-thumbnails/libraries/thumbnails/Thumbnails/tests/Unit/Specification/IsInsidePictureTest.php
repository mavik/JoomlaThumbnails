<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Thumbnails
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2024 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */

namespace Mavik\Thumbnails\Specification;

use PHPUnit\Framework\TestCase;
use Mavik\Thumbnails\Specification\Image\IsInsidePicture;
use Mavik\Thumbnails\Html\Image;

class IsInsidePictureTest extends TestCase
{
    public function testTrue()
    {
        $dom = new \DOMDocument();

        $pictureTag = $dom->createElement('picture');
        $dom->appendChild($pictureTag);

        $imgTag = $dom->createElement('img');
        $pictureTag->appendChild($imgTag);

        $image = $this->createStub(Image::class);
        $image->method('getParentElement')->willReturn($pictureTag);

        $isInsidePicture = new IsInsidePicture();
        $this->assertTrue($isInsidePicture->isSatisfiedBy($image));
    }

    public function testFalseWrongParent()
    {
        $dom = new \DOMDocument();

        $divTag = $dom->createElement('div');
        $dom->appendChild($divTag);

        $imgTag = $dom->createElement('img');
        $divTag->appendChild($imgTag);

        $image = $this->createStub(Image::class);
        $image->method('getParentElement')->willReturn($divTag);

        $isInsidePicture = new IsInsidePicture();
        $this->assertFalse($isInsidePicture->isSatisfiedBy($image));
    }

    public function testFalseNoParent()
    {
        $dom = new \DOMDocument();

        $imgTag = $dom->createElement('img');
        $dom->appendChild($imgTag);

        $image = $this->createStub(Image::class);
        $image->method('getParentElement')->willReturn(null);

        $isInsidePicture = new IsInsidePicture();
        $this->assertFalse($isInsidePicture->isSatisfiedBy($image));
    }
}
