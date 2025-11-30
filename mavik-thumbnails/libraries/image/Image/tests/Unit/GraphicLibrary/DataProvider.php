<?php
/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license MIT; see LICENSE
 */
namespace Mavik\Image\Tests\Unit\GraphicLibrary;

class DataProvider
{
    public static function imagesToOpen()
    {
        return [
            0 => [__DIR__ . '/../../resources/images/apple.jpg', IMAGETYPE_JPEG],
            1 => [__DIR__ . '/../../resources/images/butterfly_with_transparent_bg.png', IMAGETYPE_PNG],
            2 => [__DIR__ . '/../../resources/images/snowman-pixel.gif', IMAGETYPE_GIF],
            3 => [__DIR__ . '/../../resources/images/house.webp', IMAGETYPE_WEBP],
            4 => ['http://localhost:8888/apple.jpg', IMAGETYPE_JPEG],
            5 => ['https://upload.wikimedia.org/wikipedia/en/a/a7/Culinary_fruits_cropped_top_view.jpg', IMAGETYPE_JPEG],
        ];
    }
    
    public static function imagesToSave()
    {
        return [
            0 => [__DIR__ . '/../../resources/images/apple.jpg', IMAGETYPE_JPEG],
            1 => [__DIR__ . '/../../resources/images/butterfly_with_transparent_bg.png', IMAGETYPE_PNG],
            2 => [__DIR__ . '/../../resources/images/snowman-pixel.gif', IMAGETYPE_GIF],
            3 => [__DIR__ . '/../../resources/images/house.webp', IMAGETYPE_WEBP],                        
        ];
    }
    
    public static function imagesSize()
    {
        return [
            0 => [__DIR__ . '/../../resources/images/bee.gif', IMAGETYPE_GIF, 549, 619],
        ];
    }

    public static function clone()
    {
        return [
            0 => [__DIR__ . '/../../resources/images/bee.gif', IMAGETYPE_GIF, 549, 619],
        ];
    }    
    
    public static function imagesToCrop()
    {
        return [
            0 => [
                IMAGETYPE_JPEG, 25, 40, 400, 500,
                __DIR__ . '/../../resources/images/apple.jpg',
                __DIR__ . '/../../resources/images/crop/apple-25-40-400-500.jpg'
            ],
            1 => [ 
                IMAGETYPE_PNG, 250, 300, 500, 600,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.png',
                __DIR__ . '/../../resources/images/crop/butterfly_with_transparent_bg-250-300-500-600.png'
            ],
            2 => [
                IMAGETYPE_GIF, 200, 250, 300, 281,
                __DIR__ . '/../../resources/images/bee.gif',
                __DIR__ . '/../../resources/images/crop/bee-200-250-300-281.gif'
            ],
            3 => [ 
                IMAGETYPE_GIF, 300, 250, 600, 500,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.gif',
                __DIR__ . '/../../resources/images/crop/butterfly_with_transparent_bg-300-250-600-500.gif'
            ],
            4 => [
                IMAGETYPE_WEBP, 280, 320, 400, 500,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.webp',
                __DIR__ . '/../../resources/images/crop/butterfly_with_transparent_bg-280-20-400-500.webp'
            ],
        ];
    }
    
    public static function imagesToResize()
    {
        return [
            0 => [
                IMAGETYPE_JPEG, 400, 500,
                __DIR__ . '/../../resources/images/apple.jpg',
                __DIR__ . '/../../resources/images/resized/apple-400-500.jpg'
            ],
            1 => [ 
                IMAGETYPE_PNG, 300, 281,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.png',
                __DIR__ . '/../../resources/images/resized/butterfly_with_transparent_bg-300-281.png'
            ],
            2 => [
                IMAGETYPE_GIF, 200, 226,
                __DIR__ . '/../../resources/images/bee.gif',
                __DIR__ . '/../../resources/images/resized/bee-200-226.gif'
            ],
            3 => [ 
                IMAGETYPE_GIF, 300, 281,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.gif',
                __DIR__ . '/../../resources/images/resized/butterfly_with_transparent_bg-300-281.gif'
            ],
            4 => [
                IMAGETYPE_WEBP, 300, 281,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.webp',
                __DIR__ . '/../../resources/images/resized/butterfly_with_transparent_bg.webp'
            ],
        ];
    }
    
    public static function imagesToCropAndResize()
    {
        return [
            0 => [
                IMAGETYPE_JPEG, 25, 40, 400, 500, 200, 200,
                __DIR__ . '/../../resources/images/apple.jpg',
                __DIR__ . '/../../resources/images/crop-and-resize/apple-25-40-400-500-200-200.jpg'
            ],
            1 => [ 
                IMAGETYPE_PNG, 250, 300, 500, 600, 300, 400,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.png',
                __DIR__ . '/../../resources/images/crop-and-resize/butterfly_with_transparent_bg-250-300-500-600-300-400.png'
            ],
            2 => [
                IMAGETYPE_GIF, 200, 250, 300, 281, 150, 100,
                __DIR__ . '/../../resources/images/bee.gif',
                __DIR__ . '/../../resources/images/crop-and-resize/bee-200-250-300-281-150-100.gif'
            ],
            3 => [ 
                IMAGETYPE_GIF, 300, 250, 600, 500, 300, 250,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.gif',
                __DIR__ . '/../../resources/images/crop-and-resize/butterfly_with_transparent_bg-300-250-600-500-300-250.gif'
            ],
            4 => [
                IMAGETYPE_WEBP, 280, 320, 400, 500, 200, 250,
                __DIR__ . '/../../resources/images/butterfly_with_transparent_bg.webp',
                __DIR__ . '/../../resources/images/crop-and-resize/butterfly_with_transparent_bg-280-20-400-500-200-250.webp'
            ],
        ];
    }    
}
