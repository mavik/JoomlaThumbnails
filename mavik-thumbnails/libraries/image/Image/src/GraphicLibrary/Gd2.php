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
use Mavik\Image\Exception\GraphicLibraryException;
use Mavik\Image\ImageFile;

class Gd2 implements GraphicLibraryInterface
{
    const DEFAULT_CONFIGURATION = [
        'jpg_quality' => 95,
        'png_compression' => 9,
        'webp_quality' => 95,
    ];

    private $configuration = [];

    /** @var array|\SplObjectStorage */
    private $typesMap;

    public function __construct(array $configuration = [])
    {
        $this->configuration = array_merge(self::DEFAULT_CONFIGURATION, $configuration);
        if (PHP_VERSION_ID >= 80000) {
            $this->typesMap = new \SplObjectStorage();
        } else {
            $this->typesMap = [];
        }
    }

    public static function isInstalled(): bool
    {
        return function_exists('imagecreatetruecolor');
    }

    /**
     * @param resource|\GdImage $image
     * @param int $type IMAGETYPE_XXX
     */
    private function mapType($image, int $type): void
    {
        $key = is_object($image) ? $image : (string) $image;
        $this->typesMap[$key] = $type;
    }

    /**
     * @param resource|\GdImage $image
     * @return int IMAGETYPE_XXX
     */
    private function getType($image): int
    {
        $key = is_object($image) ? $image : (string) $image;
        return $this->typesMap[$key];
    }

    /**
     * @param resource|\GdImage $image
     */
    private function unmapType($image): void
    {
        $key = is_object($image) ? $image : (string) $image;
        unset($this->typesMap[$key]);
    }

    /**
     * @return resource|\GDImage
     * @throws GraphicLibraryException
     */
    public function load(ImageFile $file)
    {
        $src = $file->getPath() ?: $file->getUrl();
        $type = $file->getType();
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($src);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($src);
                imagealphablending($image, false);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($src);
                break;
            case IMAGETYPE_WEBP:
                $image = imagecreatefromwebp($src);
                break;
            default:
                throw new GraphicLibraryException('Trying to open unsupported type of image ' . image_type_to_mime_type($type));
        }
        if (!is_resource($image) && !($image instanceof \GDImage)) {
            throw new GraphicLibraryException("Cannot open image \"{$src}\"");
        }
        $this->mapType($image, $type);
        return $image;
    }

    /**
     * Load image from binary string
     * 
     * @return resource|\GDImage
     * @throws GraphicLibraryException
     */
    public function loadFromString(string $content)
    {
        $image = imagecreatefromstring($content);
        if (!$image) {
            throw new GraphicLibraryException("Cannot load the string as an image");
        }
        $info = getimagesizefromstring($content);
        $this->mapType($image, $info[2]);
        return $image;
    }

    /**
     * @param resource|\GDImage $image
     */
    public function close($image): void
    {
        $this->unmapType($image);
        imagedestroy($image);
    }

    /**
     * @param resource|\GDImage $image
     * @param int $type IMAGETYPE_XXX
     * @throws GraphicLibraryException
     */
    public function save($image, string $path, int $type): void
    {
        switch ($type) {
            case IMAGETYPE_JPEG:
                $result = imagejpeg($image, $path, $this->configuration['jpg_quality']);
                break;
            case IMAGETYPE_PNG:
                imageSaveAlpha($image, true);
                $result = imagepng($image, $path, $type);
                break;
            case IMAGETYPE_GIF:
                $result = imagegif($image, $path);
                break;
            case IMAGETYPE_WEBP:
                $result = imagewebp($image, $path, $this->configuration['webp_quality']);
                break;
            default:
                throw new GraphicLibraryException('Trying to save unsupported type of image ' . image_type_to_mime_type($type));
        }
        if (!$result) {
            throw new GraphicLibraryException("Can't write image with type '{$type}' to file '{$path}'");
        }
    }

    /**
     * @param \GDImage $image
     * @return \GDImage
     */
    public function clone($original)
    {
        $width = imagesx($original);
        $height = imagesy($original);
        if (imageistruecolor($original)) {
            $copy = imagecreatetruecolor($width, $height);
            imagealphablending($copy, false);
            imagesavealpha($copy, true);
        } else {
            $copy = imagecreate($width, $height);
            imagepalettecopy($copy, $original);
            $transparentIndex = imagecolortransparent($original);
            if ($transparentIndex >= 0) {
                $rgba = imagecolorsforindex($original, $transparentIndex);
                $newTransparentIndex = imagecolorresolve(
                    $copy,
                    $rgba['red'],
                    $rgba['green'],
                    $rgba['blue']
                );
                imagecolortransparent($copy, $newTransparentIndex);
                imagefill($copy, 0, 0, $newTransparentIndex);
            }
        }
        imagecopy($copy, $original, 0, 0, 0, 0, $width, $height);
        return $copy;
    }

    /**
     * @param \GDImage $image
     */
    public function getHeight($image): int
    {
        return imagesy($image);
    }

    /**
     * @param \GDImage $image
     */
    public function getWidth($image): int
    {
        return imagesx($image);
    }

    /**
     * @param \GDImage $image
     * @return \GDImage
     */
    public function crop($image, int $x, int $y, int $width, int $height, bool $immutable = false)
    {
        $result = imageistruecolor($image)
            ? $this->cropTrueColor($image, $x, $y, $width, $height)
            : $this->cropIndexedImage($image, $x, $y, $width, $height)
        ;
        if (empty($immutable)) {
            $this->close($image);
        }
        return $result;
    }

    /**
     * @param \GDImage $image
     * @return \GDImage
     */
    public function resize($image, int $width, int $height, bool $immutable = false)
    {
        return $this->cropAndResize($image, 0, 0, imagesx($image), imagesy($image), $width, $height, $immutable);
    }

    /**
     * @param \GDImage $image
     * @return \GDImage
     */
    public function cropAndResize($image, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, bool $immutable = false)
    {
        if (imageistruecolor($image)) {
            return $this->cropAndResizeTrueColors($image, $x, $y, $width, $height, $toWidth, $toHeight, $immutable);
        } else {
            return $this->cropAndResizeIndexedColors($image, $x, $y, $width, $height, $toWidth, $toHeight, $immutable);
        }
    }
    private function cropTrueColor(\GDImage $image, int $x, int $y, int $width, int $height): \GDImage
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefill($newImage, 0, 0, $transparent);
        imagecopy($newImage, $image, 0, 0, $x, $y, $width, $height);
        return $newImage;
    }

    private function cropIndexedImage(\GDImage $image, int $x, int $y, int $width, int $height): \GDImage
    {
        $newImage = imagecreate($width, $height);
        imagepalettecopy($newImage, $image);
        $transparentIndex = imagecolortransparent($image);
        if ($transparentIndex >= 0) {
            $transparentRgb = imagecolorsforindex($image, $transparentIndex);
            $newTransparentIndex = imagecolorresolve(
                $newImage,
                $transparentRgb['red'],
                $transparentRgb['green'],
                $transparentRgb['blue']
            );
            imagefill($newImage, 0, 0, $newTransparentIndex);
            imagecolortransparent($newImage, $newTransparentIndex);
        }
        imagecopy($newImage, $image, 0, 0, $x, $y, $width, $height);
        return $newImage;
    }

    /**
     * @param \GDImage $image
     * @return \GDImage
     */
    private function cropAndResizeTrueColors($image, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, bool $immutable)
    {
        $imageType = $this->getType($image);
        $newImage = imagecreatetruecolor($toWidth, $toHeight);
        $this->mapType($newImage, $imageType);
        if ($imageType != IMAGETYPE_JPEG) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $toWidth, $toHeight, $transparent);
        }
        imagecopyresampled($newImage, $image, 0, 0, $x, $y, $toWidth, $toHeight, $width, $height);
        if (!$immutable) {
            $this->close($image);
        }
        return $newImage;
    }

    /**
     * @param \GDImage $image
     * @return \GDImage
     */
    private function cropAndResizeIndexedColors($image, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, bool $immutable)
    {
        $newImage = imagecreatetruecolor($toWidth, $toHeight);
        $this->mapType($newImage, $this->getType($image));

        $transparentIndex = imagecolortransparent($image);
        if ($transparentIndex >= 0) {
            $transparentRgb = imagecolorsforindex($image, $transparentIndex);
            $newTransparentIndex = imagecolorresolve($newImage, $transparentRgb['red'], $transparentRgb['green'], $transparentRgb['blue']);
            imagefilledrectangle($newImage, 0, 0, $toWidth, $toHeight, $newTransparentIndex);
            imagecolortransparent($newImage, $newTransparentIndex);
        }

        imagecopyresampled($newImage, $image, 0, 0, $x, $y, $toWidth, $toHeight, $width, $height);

        $colorNumbers = imagecolorstotal($image);
        imagetruecolortopalette($newImage, false, $colorNumbers);

        if ($transparentIndex >= 0) {
            $newTransparentIndex = imagecolorresolve($newImage, $transparentRgb['red'], $transparentRgb['green'], $transparentRgb['blue']);
            imagecolortransparent($newImage, $newTransparentIndex);
        }

        if (!$immutable) {
            $this->close($image);
        }
        return $newImage;
    }
}