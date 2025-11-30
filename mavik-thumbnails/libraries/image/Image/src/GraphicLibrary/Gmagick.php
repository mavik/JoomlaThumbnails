<?php
declare(strict_types=1);

/**
 * PHP Library for Image processing and creating thumbnails
 *
 * @package Mavik\Image
 * @author Vitalii Marenkov <admin@mavik.com.ua>
 * @copyright 2021 Vitalii Marenkov
 * @license GNU General Public License version 2 or later; see LICENSE
 */
namespace Mavik\Image\GraphicLibrary;

use Mavik\Image\GraphicLibraryInterface;
use Mavik\Image\ImageFile;
use Mavik\Image\Exception\GraphicLibraryException;

class Gmagick implements GraphicLibraryInterface
{
    const DEFAULT_CONFIGURATION = [
        'jpg_quality' => 95,
        'png_compression' => 9,
        'webp_quality' => 95,
    ];
    
    const TYPES = [
        IMAGETYPE_JPEG => 'JPG',
        IMAGETYPE_PNG => 'PNG',
        IMAGETYPE_GIF => 'GIF',
        IMAGETYPE_WEBP => 'WebP'
    ];

    private $configuration = [];

    public function __construct(array $configuration = [])
    {
        $this->configuration = array_merge(self::DEFAULT_CONFIGURATION, $configuration);
    }
    
    public static function isInstalled(): bool
    {
        return class_exists('Gmagick');
    }
    
    /**
     * @throws GraphicLibraryException
     */
    public function load(ImageFile $imageFile): \Gmagick
    {
        /**
         * We cannot use
         * return new \Gmagick($src);
         * because Gmagick doesn't support wrappers (https://, ftp:// itp.)
         * 
         * We cannot use
         * $fp = fopen($src, 'rb');
         * $image = new \Gmagick();
         * return $image->readimagefile($fp);
         * because it causes Segmentation fault.
         */ 
        $image = new \Gmagick();
        try {
            if ($imageFile->getPath()) {
                $image->readimage($imageFile->getPath());
            } else {
                $context = stream_context_create([
                    'http' => [
                        'header' => "User-Agent: mavikImage/1.0",
                    ]
                ]);   
                $image->readimageblob(file_get_contents($imageFile->getUrl(), false, $context));
            }
        } catch (\Exception $e) {
            throw new GraphicLibraryException($e->getMessage());
        }
        return $image;
    }
    
    /**
     * Load image from binary string
     * 
     * @throws GraphicLibraryException
     */    
    public function loadFromString(string $content): \Gmagick
    {
        try {
            $image = new \Gmagick();
            $image->readimageblob($content);
        } catch (\Exception $e) {
            throw new GraphicLibraryException($e->getMessage());
        }
        return $image;
    }

    public function close($image): void
    {
        unset($image);
    }

    /**
     * @param \Gmagick $image
     * @param int $type IMAGETYPE_XXX
     * @throws GraphicLibraryException
     */
    public function save($image, string $path, int $type): void
    {
        if (!$image->setimageformat(self::TYPES[$type])) {
            throw new GraphicLibraryException("Can't write image with type '{$type}' to file '{$path}'");
        }
        $image->writeimage($path);
    }

    /**
     * @param \Gmagick $image
     */
    public function clone($image): \Gmagick
    {
        return clone $image;
    }    

    /**
     * @param \Gmagick $image
     */
    public function getHeight($image): int
    {
        return $image->getimageheight();
    }

    /**
     * @param \Gmagick $image
     */
    public function getWidth($image): int
    {
        return $image->getimagewidth();
    }
    
    /**
     * @param \Gmagick $image
     */
    public function crop($image, int $x, int $y, int $width, int $height, bool $immutable = false): \Gmagick
    {
        $tmpImage = $immutable ? clone $image : $image;
        $tmpImage->cropImage($width, $height, $x, $y);
        return $tmpImage;
    }

    /**
     * @param \Gmagick $image
     */
    public function resize($image, int $width, int $height, bool $immutable = false): \Gmagick
    {
        $tmpImage = $immutable ? clone $image : $image;
        $tmpImage->resizeimage($width, $height, \Gmagick::FILTER_TRIANGLE, 1);
        return $tmpImage;
    }

    /**
     * @param \Gmagick $image
     */
    public function cropAndResize($image, $x, $y, $width, $height, $toWidth, $toHeight, bool $immutable = false): \Gmagick
    {
        $tmpImage = $immutable ? clone $image : $image;
        $cropedImage = $this->crop($tmpImage, $x, $y, $width, $height);
        return $this->resize($cropedImage, $toWidth, $toHeight);
    }
}
