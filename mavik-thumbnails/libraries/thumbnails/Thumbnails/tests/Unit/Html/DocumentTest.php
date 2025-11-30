<?php
/**
 * PHP Library for replacing images in html to thumbnails.
 *
 * @package Mavik\Thumbnails
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2023 Vitalii Marenkov
 * @license MIT; see LICENSE
 */

namespace Mavik\Thumbnails\Html;

use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{    
    /**
     * @covers Document::findImages
     * @dataProvider dataProvider
     */
    public function testFindImages(string $html, array $images)
    {
        $document = Document::createFragment($html);
        $dom = new \DOMDocument();
        foreach ($document->findImages() as $index => $image) {
            /** @var Image $image */
            $imgTag = $dom->createElement('img');
            $imgTag->setAttribute('src', $images[$index]['src']);
            $imgTag->setAttribute('width', $images[$index]['width']);
            $imgTag->setAttribute('height', $images[$index]['height']);
            $imgTag->setAttribute('style', $images[$index]['style']);
            $testImage = new Image($imgTag);       
            $this->assertEquals($testImage->getAttribute('src'), $image->getAttribute('src'));
            $this->assertEquals($testImage->getAttribute('width'), $image->getAttribute('width'));
            $this->assertEquals($testImage->getAttribute('height'), $image->getAttribute('height'));
            $this->assertEquals($testImage->getAttribute('style'), $image->getAttribute('style'));
        }
    }
    
    public function dataProvider(): array
    {
        return [
            0 => [
                'START adsf <img src="test0" width="10"
                height="20"> fg sdg
                asfsf asf <div> <img width="20px" height="30 px" src="test1" />
                <p>adssdf sadf <img width="20px" height="30 px" src="test2" style="width: 40px; height: 50 px">
                <style>font-size: 12px</style>
                <img width="30px" src="test3" style="height: 50 px"></img>
                <img height="30px" src="test4" style="width: 50px"></img>
                <script>alert("TEST")</script>
                FINISH',
                [
                    [
                        'src' => 'test0',
                        'width' => '10',
                        'height'=> '20',
                        'style' => null,
                    ], [
                        'src' => 'test1',
                        'width' => '20px',
                        'height'=> '30 px',
                        'style' => null,
                    ], [
                        'src' => 'test2',
                        'width' => '20px',
                        'height'=> '30 px',
                        'style' => 'width: 40px; height: 50 px',
                    ], [
                        'src' => 'test3',
                        'width' => '30px',
                        'height'=> null,
                        'style' => 'height: 50 px',
                    ], [
                        'src' => 'test4',
                        'width' => null,
                        'height'=> '30px',
                        'style' => 'width: 50px',
                    ],
                ],
            ],
        ];
    }
}