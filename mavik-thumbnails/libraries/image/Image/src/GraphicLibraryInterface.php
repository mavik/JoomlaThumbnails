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
namespace Mavik\Image;

interface GraphicLibraryInterface
{
    public function __construct(array $configuration = []);

    public static function isInstalled(): bool;
    
    /**
     * Load image from file
     * 
     * @return mixed Resource or object, depends on graphic library
     */
    public function load(ImageFile $file);
    
    /**
     * Load image from binary string
     * 
     * @return mixed Resource or object, depends on graphic library
     */
    public function loadFromString(string $content);

    /**
     * Save image to file
     * 
     * @param mixed $image Type depends on graphic library
     * @param int $type IMAGETYPE_XXX
     * @return void
     */
    public function save($image, string $path, int $type): void;
    
    /**
     * Free resources
     * 
     * @param mixed $image Type depends on graphic library
     */
    public function close($image): void;

    /**
     * @param mixed $image Depends on graphic library
     * @return mixed Image - resource or object, it depends on graphic library
     */    
    public function clone($image);

    /**
     * @param mixed $image Type depends on graphic library
     */
    public function getWidth($image): int;
    
    /**
     * @param mixed $image Type depends on graphic library
     */
    public function getHeight($image): int;
    
    /**
     * @param mixed $image Type depends on graphic library
     * @return mixed Image - resource or object, it depends on graphic library
     */
    public function crop($image, int $x, int $y, int $width, int $height, bool $immutable = false);

    /**
     * @param mixed $image Depends on graphic library
     * @return mixed Image - resource or object, it depends on graphic library
     */
    public function resize(
        $image, 
        int $width, 
        int $height,
        bool $immutable = false
    );
    
    /**
     * It can be used for creating thumbnails.
     * 
     * Some library can do it as one operation,
     * and we don't want use two operations with these libraries
     * 
     * @param mixed $image Type depends on graphic library
     * @return mixed Image - resource or object, it depends on graphic library
     */
    public function cropAndResize(
        $image, 
        int $x, 
        int $y, 
        int $width, 
        int $height, 
        int $toWidth, 
        int $toHeight, 
        bool $immutable = false
    );
}
