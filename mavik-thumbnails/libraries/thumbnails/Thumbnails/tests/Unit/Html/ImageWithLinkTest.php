<?php
/**
 * PHP Library for replacing images in html to thumbnails.
 *
 * @package Mavik\Thumbnails
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2024 Vitalii Marenkov
 * @license MIT; see LICENSE
 */

namespace Mavik\Thumbnails\Html;

use Mavik\Image\ImageFactory;
use Mavik\Image\ImageImmutable;
use PHPUnit\Framework\TestCase;

class ImageWithLinkTest extends TestCase
{
    /** @var ImageFactory&\PHPUnit\Framework\MockObject\MockObject */
    private ImageFactory $imageFactory;

    protected function setUp(): void
    {
        $this->imageFactory = $this->createMock(ImageFactory::class);
        $this->imageFactory->method('createImmutable')->willReturn($this->createStub(ImageImmutable::class));
    }

    /**
     * @covers \Mavik\Thumbnails\Html\ImageWithLink::__construct
     */
    public function testConstruct(): void
    {
        $dom = new \DOMDocument();
        $parent = $dom->createElement('div');
        $img = $dom->createElement('img');
        $img->setAttribute('src', 'test.jpg');
        $parent->appendChild($img);

        $imageWithLink = new ImageWithLink($img, $this->imageFactory, $img->getAttribute('src'));

        $this->assertEquals(1, $parent->childNodes->length);

        /** @var \DOMElement $link */
        $link = $parent->firstChild;
        $this->assertInstanceOf(\DOMElement::class, $link);
        $this->assertEquals('a', $link->nodeName);
        $this->assertEquals('test.jpg', $link->getAttribute('href'));
        $this->assertEquals('mavik-thumbnails-link', $link->getAttribute('class'));

        $this->assertEquals(1, $link->childNodes->length);
        $this->assertSame($img, $link->firstChild);
    }

    /**
     * @covers \Mavik\Thumbnails\Html\ImageWithLink::__construct
     */
    public function testConstructWithoutParentNode(): void
    {
        $dom = new \DOMDocument();
        $img = $dom->createElement('img');
        $img->setAttribute('src', 'test.jpg');

        $imageWithLink = new ImageWithLink($img, $this->imageFactory, $img->getAttribute('src'));

        // The image is now a child of the created 'a' element
        $link = $img->parentNode;
        $this->assertInstanceOf(\DOMElement::class, $link);
        $this->assertEquals('a', $link->nodeName);
        $this->assertEquals('test.jpg', $link->getAttribute('href'));
        $this->assertEquals('mavik-thumbnails-link', $link->getAttribute('class'));

        $this->assertSame($img, $link->firstChild);
    }

    /**
     * @covers \Mavik\Thumbnails\Html\ImageWithLink::createFromImage
     */
    public function testCreateFromImage(): void
    {
        $dom = new \DOMDocument();
        $img = $dom->createElement('img');
        $img->setAttribute('src', 'test.jpg');

        $image = new Image($img, $this->imageFactory);
        $imageWithLink = ImageWithLink::createFromImage($image, $image->getSrc());

        $link = $img->parentNode;
        $this->assertInstanceOf(\DOMElement::class, $link);
        $this->assertEquals('a', $link->nodeName);
        $this->assertEquals('test.jpg', $link->getAttribute('href'));
        $this->assertInstanceOf(ImageWithLink::class, $imageWithLink);
    }

    /**
     * @covers \Mavik\Thumbnails\Html\ImageWithLink::getParentNode
     */
    public function testGetParentNode(): void
    {
        $dom = new \DOMDocument();
        $parent = $dom->createElement('div');
        $img = $dom->createElement('img');
        $parent->appendChild($img);

        $imageWithLink = new ImageWithLink($img, $this->imageFactory, $img->getAttribute('src'));
        $this->assertSame($parent, $imageWithLink->getParentNode());
    }

    /**
     * @covers \Mavik\Thumbnails\Html\ImageWithLink::getParentNode
     */
    public function testGetParentNodeWhenNull(): void
    {
        $dom = new \DOMDocument();
        $img = $dom->createElement('img');

        $imageWithLink = new ImageWithLink($img, $this->imageFactory, $img->getAttribute('src'));
        $this->assertNull($imageWithLink->getParentNode());
    }

    /**
     * @covers \Mavik\Thumbnails\Html\ImageWithLink::addLinkClass
     */
    public function testAddLinkClass(): void
    {
        $dom = new \DOMDocument();
        $img = $dom->createElement('img');
        $imageWithLink = new ImageWithLink($img, $this->imageFactory, $img->getAttribute('src'));
        /** @var \DOMElement $link */
        $link = $img->parentNode;

        // Add first class
        $imageWithLink->addLinkClass('custom-class-1');
        $this->assertEquals('mavik-thumbnails-link custom-class-1', $link->getAttribute('class'));

        // Add second class
        $imageWithLink->addLinkClass('custom-class-2');
        $this->assertEquals('mavik-thumbnails-link custom-class-1 custom-class-2', $link->getAttribute('class'));

        // Add a class that already exists
        $imageWithLink->addLinkClass('custom-class-1');
        $this->assertEquals('mavik-thumbnails-link custom-class-1 custom-class-2', $link->getAttribute('class'));
    }

    /**
     * @covers \Mavik\Thumbnails\Html\ImageWithLink::setLinkAttribute
     */
    public function testSetLinkAttribute(): void
    {
        $dom = new \DOMDocument();
        $img = $dom->createElement('img');
        $imageWithLink = new ImageWithLink($img, $this->imageFactory, $img->getAttribute('src'));
        /** @var \DOMElement $link */
        $link = $img->parentNode;

        $imageWithLink->setLinkAttribute('rel', 'lightbox');
        $this->assertEquals('lightbox', $link->getAttribute('rel'));

        $imageWithLink->setLinkAttribute('data-title', 'Image Title');
        $this->assertEquals('Image Title', $link->getAttribute('data-title'));
    }
}
