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
use Mavik\Image\ImageWithThumbnails;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{    
    /**
     * @covers ::getWidth
     * @covers ::getWidthUnit
     * @dataProvider dataProvider
     */
    public function testWidth(\DOMElement $dom, array $params)
    {
        $imageFactory = $this->createMock(ImageFactory::class);
        $imageFactory->method('createImmutable')->willReturn($this->createStub(ImageImmutable::class));
        $image = new Image($dom, $imageFactory);
        $this->assertEquals($params['width'], $image->getWidth());
        $this->assertEquals($params['widthUnit'], $image->getWidthUnit());
    }
    
    /**
     * @covers ::getHeight
     * @covers ::getHeightUnit
     * @dataProvider dataProvider
     */
    public function testGetHeight(\DOMElement $dom, array $params)
    {
        $imageFactory = $this->createMock(ImageFactory::class);
        $imageFactory->method('createImmutable')->willReturn($this->createStub(ImageImmutable::class));
        $image = new Image($dom, $imageFactory);
        $this->assertEquals($params['height'], $image->getHeight());
        $this->assertEquals($params['heightUnit'], $image->getHeightUnit());
    }
    
    /**
     * @covers ::isSizeInPixels
     * @dataProvider dataProvider
     */
    public function testIsSizeInPixels(\DOMElement $dom, array $params)
    {
        $imageFactory = $this->createMock(ImageFactory::class);
        $imageFactory->method('createImmutable')->willReturn($this->createStub(ImageImmutable::class));
        $image = new Image($dom, $imageFactory);
        $this->assertEquals($params['isSizeInPixels'], $image->isSizeInPixels());
    }
    
    /**
     * @covers ::isWidthInStyle
     * @dataProvider dataProvider
     */
    public function testIsWidthInStyle(\DOMElement $dom, array $params)
    {
        $imageFactory = $this->createMock(ImageFactory::class);
        $imageFactory->method('createImmutable')->willReturn($this->createStub(ImageImmutable::class));
        $image = new Image($dom, $imageFactory);
        $this->assertEquals($params['isWidthInStyle'], $image->isWidthInStyle());
    }

    /**
     * @covers ::isHeightInStyle
     * @dataProvider dataProvider
     */
    public function testIsHeightInStyle(\DOMElement $dom, array $params)
    {
        $imageFactory = $this->createMock(ImageFactory::class);
        $imageFactory->method('createImmutable')->willReturn($this->createStub(ImageImmutable::class));
        $image = new Image($dom, $imageFactory);
        $this->assertEquals($params['isHeightInStyle'], $image->isHeightInStyle());
    }
    
    public function dataProvider(): \Generator
    {
        $data = [
            [
                'attributes' => [
                    'src' => 'test0.jpg',
                    'height' => 10,
                    'width' => 20,
                ],
                'src' => 'test0.jpg',
                'height' => 10,
                'heightUnit' => 'px',
                'width' => 20,
                'widthUnit' => 'px',
                'isSizeInPixels' => true,
                'isWidthInStyle' => false,
                'isHeightInStyle' => false,
            ], [
                'attributes' => [
                    'src' => 'test1.jpg',
                    'height' => '20px',
                    'width' => '30px',
                ],
                'src' => 'test1.jpg',
                'height' => 20,
                'heightUnit' => 'px',
                'width' => 30,
                'widthUnit' => 'px',
                'isSizeInPixels' => true,
                'isWidthInStyle' => false,
                'isHeightInStyle' => false,
            ], [
                'attributes' => [
                    'src' => 'test2.jpg',
                    'height' => '30%',
                    'width' => '40%',
                ],
                'src' => 'test2.jpg',
                'height' => 30,
                'heightUnit' => '%',
                'width' => 40,
                'widthUnit' => '%',
                'isSizeInPixels' => false,
                'isWidthInStyle' => false,
                'isHeightInStyle' => false,
            ], [
                'attributes' => [
                    'src' => 'test3.jpg',
                    'height' => '40',
                    'width' => '50',
                    'style' => 'margin: 0; width: 60px',
                ],
                'src' => 'test3.jpg',
                'height' => 40,
                'heightUnit' => 'px',
                'width' => 60,
                'widthUnit' => 'px',
                'isSizeInPixels' => true,
                'isWidthInStyle' => true,
                'isHeightInStyle' => false,
            ], [
                'attributes' => [
                    'src' => 'test4.jpg',
                    'height' => '40',
                    'width' => '50',
                    'style' => 'margin: 0; height: 70px',
                ],
                'src' => 'test4.jpg',
                'height' => 70,
                'heightUnit' => 'px',
                'width' => 50,
                'widthUnit' => 'px',
                'isSizeInPixels' => true,
                'isWidthInStyle' => false,
                'isHeightInStyle' => true,
            ], [
                'attributes' => [
                    'src' => 'test5.jpg',
                    'height' => '40',
                    'width' => '50',
                    'style' => 'width: 80; height: 90',
                ],
                'src' => 'test5.jpg',
                'height' => 40,
                'heightUnit' => 'px',
                'width' => 50,
                'widthUnit' => 'px',
                'isSizeInPixels' => true,
                'isWidthInStyle' => false,
                'isHeightInStyle' => false,
            ], [
                'attributes' => [
                    'src' => 'test6.jpg',
                    'height' => '40',
                    'width' => '50',
                    'style' => 'width: 90%; height: 100%',
                ],
                'src' => 'test6.jpg',
                'height' => 100,
                'heightUnit' => '%',
                'width' => 90,
                'widthUnit' => '%',
                'isSizeInPixels' => false,
                'isWidthInStyle' => true,
                'isHeightInStyle' => true,
            ]
        ];
        $dom = new \DOMDocument();
        foreach ($data as $item) {
            $img = $dom->createElement('img');
            $img->setAttribute('src', $item['attributes']['src']);
            if (!empty($item['attributes']['width'])) {
                $img->setAttribute('width', $item['attributes']['width']);
            }
            if (!empty($item['attributes']['height'])) {
                $img->setAttribute('height', $item['attributes']['height']);
            }
            if (!empty($item['attributes']['style'])) {
                $img->setAttribute('style', $item['attributes']['style']);
            }
            yield [$img, $item];
        }
    }

    /**
     * @covers ::useThumbnail
     */
    public function testUseThumbnailWithMissing1xScale(): void
    {
        $dom = new \DOMDocument();
        $img = $dom->createElement('img');
        $img->setAttribute('src', 'test.jpg');
        $img->setAttribute('width', '100');
        $img->setAttribute('height', '50');

        $thumbnail2x = $this->createConfiguredMock(ImageImmutable::class, [
            'getUrl' => 'test-2x.jpg',
            'getWidth' => 400,
            'getHeight' => 200,
        ]);
        $thumbnail3x = $this->createConfiguredMock(ImageImmutable::class, [
            'getUrl' => 'test-3x.jpg',
            'getWidth' => 600,
            'getHeight' => 300,
        ]);

        $imageWithThumbnails = $this->createConfiguredMock(ImageWithThumbnails::class, [
            'thumbnails' => [2 => $thumbnail2x, 3 => $thumbnail3x]
        ]);

        $imageFactory = $this->createMock(ImageFactory::class);
        $imageFactory->method('createImmutable')->willReturn($this->createStub(ImageImmutable::class));
        $imageFactory->expects($this->once())
            ->method('convertImageToImageWithThumbnails')
            ->willReturn($imageWithThumbnails);

        $image = new Image($img, $imageFactory);
        $result = $image->useThumbnail('scale_by_width', [2, 3]);

        $this->assertTrue($result);
        $this->assertEquals(200, $image->getWidth());
        $this->assertEquals(100, $image->getHeight());
        $this->assertEquals('test-2x.jpg', $img->getAttribute('src'));
        $this->assertEquals('test-2x.jpg 2x, test-3x.jpg 3x', $img->getAttribute('srcset'));
    }
}