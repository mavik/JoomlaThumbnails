<?php
/**
 * PHP Library for replacing images in html to thumbnails.
 *
 * @package Mavik\Thumbnails
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2024 Vitalii Marenkov
 * @license MIT; see LICENSE
 */

namespace Mavik\Thumbnails\Action;

use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Configuration\Base;
use Mavik\Thumbnails\Configuration\Server;
use Mavik\Thumbnails\Html\Image;
use Mavik\Thumbnails\JsAndCss;
use Mavik\Image\ImageFactory;
use Mavik\Image\ImageWithThumbnails;
use Mavik\Image\ImageImmutable;
use PHPUnit\Framework\TestCase;

class ReplaceToThumbnailTest extends TestCase
{
    /**
     * @covers \Mavik\Thumbnails\Action\ReplaceToThumbnail::__invoke
     */
    public function testReplaceToThumbnail()
    {
        $src = 'test.jpg';
        $width = 200;
        $height = 100;
        $scales = [1, 2];
        $scrset = 'thumb-1-test.jpg 1x, thumb-2-test.jpg 2x';

        $server = new Server('http://example.com', '/var/www/html', 'thumbnails');
        $base = new Base('Stretch', $scales);
        $configuration = new Configuration($server, $base);

        $imageTag = $this->createImgTag($src, $width, $height);
        $imageFactory = $this->createImageFactory($imageTag, $configuration);
        $image = new Image($imageTag, $imageFactory);
        $jsAndCss = new JsAndCss();

        $replaceToThumbnail = new ReplaceToThumbnail($configuration);

        $replaceToThumbnail($image, $jsAndCss);

        $this->assertEquals($this->thumbName($src, 1), $imageTag->getAttribute('src'));
        $this->assertEquals($scrset, $imageTag->getAttribute('srcset'));
    }

    private function createImgTag(string $src, int $width, int $height): \DOMElement
    {
        $domDocument = new \DOMDocument();
        $imageTag = $domDocument->createElement('img');
        $imageTag->setAttribute('src', $src);
        $imageTag->setAttribute('width', $width);
        $imageTag->setAttribute('height', $height);
        return $imageTag;
    }


    private function createImageFactory(
        \DOMElement $imageTag,
        Configuration $configurationThumbnails
    ): ImageFactory {
        $src = $imageTag->getAttribute('src');
        $width = $imageTag->getAttribute('width');
        $height = $imageTag->getAttribute('height');
        $scales = $configurationThumbnails->base()->scales();
        $resizeType = $configurationThumbnails->base()->resizeType();

        $imageFactory = $this->createMock(ImageFactory::class);

        $originalImage = $this->createStub(ImageImmutable::class);
        $imageFactory->method('createImmutable')->willReturn($originalImage);

        $imageFactory
            ->expects($this->once())
            ->method('convertImageToImageWithThumbnails')
            ->with(
                $this->equalTo($originalImage),
                $this->equalTo($width),
                $this->equalTo($height),
                $resizeType,
                $scales
            )->willReturn($this->createImageWithThumbnails($src, $width, $height, $scales))
        ;

        return $imageFactory;
    }

    private function createImageWithThumbnails(
        string $src,
        int $width,
        int $height,
        array $scales
    ): ImageWithThumbnails {
        $imageWithThumbnails = $this->createStub(ImageWithThumbnails::class);
        $imageWithThumbnails->method('thumbnails')->willReturn($this->createThumbnails($src, $width, $height, $scales));
        return $imageWithThumbnails;
    }

    /**
     * @return ImageImmutable[]
     */
    private function createThumbnails($src, int $width, int $height, array $scales): array
    {
        $thumbnails = [];
        foreach ($scales as $scale) {
            $thumbnails[$scale] = $this->createStub(ImageImmutable::class);
            $thumbnails[$scale]
                ->method('getWidth')
                ->willReturn($width * $scale)
            ;
            $thumbnails[$scale]
                ->method('getHeight')
                ->willReturn($height * $scale)
            ;
            $thumbnails[$scale]
                ->method('getUrl')
                ->willReturn($this->thumbName($src, $scale))
            ;
        }
        return $thumbnails;
    }

    private function thumbName(string $src, float $scale): string
    {
        return "thumb-{$scale}-{$src}";
    }
}
