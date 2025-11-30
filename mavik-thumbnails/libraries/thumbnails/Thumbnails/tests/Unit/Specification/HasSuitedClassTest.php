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

namespace Mavik\Thumbnails\Specification;

use PHPUnit\Framework\TestCase;
use Mavik\Thumbnails\Configuration;

class HasSuitedClassTest extends TestCase
{
    /**
     * @covers IsImageToReplace::__invoke
     * @dataProvider dataProvider
     */
    public function test(string $imgClass, array $include, array $exclude, bool $result): void
    {
        $dom = new \DOMDocument();
        $imgTag = $dom->createElement('img');
        $imgTag->setAttribute('class', $imgClass);
        $configuration = new Configuration(
            new Configuration\Server('', ''),
            new Configuration\Base('', [1], $include, $exclude)
        );
        $hasSuitedClass = new HasSuitedClass($configuration);        
        $this->assertEquals($result, $hasSuitedClass($imgTag));
    }
    
    public function dataProvider(): array
    {
        return [
            0 => [
                "",
                [],
                [],
                true,
            ],
            1 => [
                "class1",
                [],
                [],
                true,
            ],
            2 => [
                "",
                ['class1'],
                [],
                false,
            ],
            3 => [
                "class1",
                ['class1'],
                [],
                true,
            ],
            4 => [
                " class1 ",
                ['class1', 'class2'],
                [],
                true,        
            ],
            5 => [
                " class1 \n class2 ",
                ['class2', 'class3'],
                [],
                true,
            ],
            6 => [
                " class1 \n class2 ",
                ['class2'],
                [],
                true,                
            ],
            7 => [
                " class1 \n class2 ",
                ['class3'],
                [],
                false,
            ],
            8 => [
                " class1 \n class2 ",
                ['class3', 'class4'],
                [],
                false,
            ],
            9 => [
                " class1 \n class2 ",
                [],
                ['class2'],
                false,
            ],
            10 => [
                " class1 \n class2 ",
                ['class1'],
                ['class2', 'class3'],
                false,
            ],
        ];
    }
}
