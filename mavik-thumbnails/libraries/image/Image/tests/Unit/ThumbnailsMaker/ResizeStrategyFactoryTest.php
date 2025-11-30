<?php
/*
 * PHP Library for Image processing and creating thumbnails
 *
 * @package Mavik\Image
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2021 Vitalii Marenkov
 * @license MIT; see LICENSE
*/

namespace Mavik\Image\ThumbnailsMaker;

use PHPUnit\Framework\TestCase;

class ResizeStrategyFactoryTest extends TestCase
{
    public function testSuccess()
    {
        $strategy = ResizeStrategyFactory::create('fIlL');
        $this->assertInstanceOf(ResizeStrategyInterface::class, $strategy);
    }
    
    public function testFailure()
    {
        $this->expectException(\InvalidArgumentException::class);
        ResizeStrategyFactory::create('noResizeStrategy');
    }
}
