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

class FileNameTest extends TestCase
{   
    /**
     * @covers FileName::getPath
     * @dataProvider files
     */
    public function testGetPath(array $config, string $src, string $path = null, string $url = null)
    {
        $fileName = new FileName($src, $config['base_uri'], $config['web_root_dir']);
        $this->assertEquals($path, $fileName->getPath());
    }
    
    /**
     * @covers FileName::getPath
     * @dataProvider files
     */
    public function testGetUrl(array $config, string $src, string $path = null, string $url = null)
    {
        $fileName = new FileName($src, $config['base_uri'], $config['web_root_dir']);
        $this->assertEquals($url, $fileName->getUrl());
    }
        
    public function files()
    {
        $webRootDir = realpath(__DIR__ . '/../resources') . '/';
        
        /**
         * config
         * tested src
         * expected path
         * expected url
         */
        return [
            [   0 =>
                [
                    'base_uri' => 'http://test.com/',
                    'web_root_dir' => $webRootDir
                ],
                'http://test.com/images/apple.jpg',
                realpath(__DIR__ . '/../resources/images/apple.jpg'),
                'http://test.com/images/apple.jpg'
            ],[ 1 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                'http://test.com/resources/images/apple.jpg',
                realpath(__DIR__ . '/../resources/images/apple.jpg'),
                'http://test.com/resources/images/apple.jpg'
            ],[ 2 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                'http://test2.com/resources/images/apple.jpg',
                null,
                'http://test2.com/resources/images/apple.jpg',
            ],[ 3 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                'http://test.com/site2/images/apple.jpg',
                null,
                'http://test.com/site2/images/apple.jpg'
            ],[ 4 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                '/resources/images/apple.jpg',
                realpath(__DIR__ . '/../resources/images/apple.jpg'),
                'http://test.com/resources/images/apple.jpg'
            ],[ 5 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                'images/apple.jpg',
                realpath(__DIR__ . '/../resources/images/apple.jpg'),
                'http://test.com/resources/images/apple.jpg'
            ],[ 6 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                realpath(__DIR__ . '/../resources/images/apple.jpg'),
                realpath(__DIR__ . '/../resources/images/apple.jpg'),
                'http://test.com/resources/images/apple.jpg'
            ],[ 7 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                __DIR__ . '/../resources/images/apple.jpg',
                realpath(__DIR__ . '/../resources/images/apple.jpg'),
                'http://test.com/resources/images/apple.jpg'
            ],[ 8 =>
                [
                    'base_uri' => 'http://test.com/resources/',
                    'web_root_dir' => $webRootDir
                ],
                '/src/../resources/images/beach.webp',
                realpath(__DIR__ . '/../resources/images/beach.webp'),
                'http://test.com/resources/images/beach.webp'
            ]
        ];
    }
}