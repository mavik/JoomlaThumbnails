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
use Mavik\Thumbnails\Specification\Image\IsZoomedOut;
use Mavik\Thumbnails\Html\Image;

class IsZoomedOutTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function test(bool $isSizeInPixels, ?float $height, int $realHeight, ?float $width, int $realWidth, bool $expected): void
    {
        $image = $this->createStub(Image::class);
        $image->method('isSizeInPixels')->willReturn($isSizeInPixels);
        $image->method('getHeight')->willReturn($height);
        $image->method('getRealHeight')->willReturn($realHeight);
        $image->method('getWidth')->willReturn($width);
        $image->method('getRealWidth')->willReturn($realWidth);

        $specification = new IsZoomedOut();
        $this->assertSame($expected, $specification->isSatisfiedBy($image));
    }

    public static function dataProvider(): array
    {
        return [
            'not in pixels' => [false, 100.0, 200, 100.0, 200, false],
            'zoomed out by height' => [true, 100.0, 200, null, 0, true],
            'zoomed out by width' => [true, null, 0, 100.0, 200, true],
            'zoomed out by both' => [true, 100.0, 200, 100.0, 200, true],
            'zoomed out height, width equal' => [true, 100.0, 200, 200.0, 200, true],
            'zoomed out width, height equal' => [true, 200.0, 200, 100.0, 200, true],
            'not zoomed out, both equal' => [true, 200.0, 200, 200.0, 200, false],
            'not zoomed out, larger height' => [true, 300.0, 200, 200.0, 200, false],
            'not zoomed out, larger width' => [true, 200.0, 200, 300.0, 200, false],
            'null values' => [true, null, 0, null, 0, false],
            'null height, not zoomed out width' => [true, null, 0, 300.0, 200, false],
            'null width, not zoomed out height' => [true, 300.0, 200, null, 0, false],
        ];
    }
}
