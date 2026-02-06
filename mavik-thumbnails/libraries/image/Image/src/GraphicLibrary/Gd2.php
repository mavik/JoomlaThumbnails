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

    private array $configuration = [];
    private \SplObjectStorage $typesMap;

    public function __construct(array $configuration = [])
    {
        $this->configuration = array_merge(self::DEFAULT_CONFIGURATION, $configuration);
        $this->typesMap = new \SplObjectStorage();
    }

    public static function isInstalled(): bool
    {
        return function_exists('imagecreatetruecolor');
    }

    /**
     * @param int $type IMAGETYPE_XXX
     */
    private function mapType(\GdImage $image, int $type): void
    {
        $this->typesMap[$image] = $type;
    }

    /**
     * @return int IMAGETYPE_XXX
     */
    private function getType(\GdImage $image): int
    {
        return (int) $this->typesMap[$image];
    }

    /**
     * @param \GdImage $image
     */
    private function unmapType(\GdImage $image): void
    {
        unset($this->typesMap[$image]);
    }

    /**
     * @throws GraphicLibraryException
     */
    public function load(ImageFile $file): \GdImage
    {
        $src = $file->getPath() ?: $file->getUrl();
        $type = $file->getType();
        switch ($type) {
            case IMAGETYPE_JPEG:
                $image = @imagecreatefromjpeg($src);
                break;
            case IMAGETYPE_PNG:
                $image = @imagecreatefrompng($src);
                if ($image) {
                    imagealphablending($image, false);
                }
                break;
            case IMAGETYPE_GIF:
                $image = @imagecreatefromgif($src);
                break;
            case IMAGETYPE_WEBP:
                $image = @imagecreatefromwebp($src);
                break;
            default:
                throw new GraphicLibraryException('Trying to open unsupported type of image ' . image_type_to_mime_type($type));
        }
        if (!$image instanceof \GdImage && !is_resource($image)) {
            throw new GraphicLibraryException("Cannot open image \"{$src}\"");
        }
        $this->mapType($image, $type);
        return $image;
    }

    /**
     * @throws GraphicLibraryException
     */
    public function loadFromString(string $content): \GdImage
    {
        $image = @imagecreatefromstring($content);
        if (!$image instanceof \GdImage && !is_resource($image)) {
            throw new GraphicLibraryException("Cannot load the string as an image");
        }
        $info = getimagesizefromstring($content);
        if (!$info) {
            throw new GraphicLibraryException("Cannot get image size from string");
        }
        $this->mapType($image, $info[2]);
        return $image;
    }

    /**
     * @param \GdImage $image
     */
    public function close($image): void
    {
        $this->unmapType($image);
        imagedestroy($image);
    }

    /**
     * @param \GdImage $image
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
                if ($this->getType($image) !== IMAGETYPE_JPEG) {
                    imageSaveAlpha($image, true);
                }
                $result = imagepng($image, $path, $this->configuration['png_compression']);
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
            throw new GraphicLibraryException("Cannot save image to \"{$path}\"");
        }
    }

    /**
     * @param \GdImage|resource $original
     */
    public function clone($original): \GdImage
    {
        $width = imagesx($original);
        $height = imagesy($original);
        return $this->crop($original, 0, 0, $width, $height, true);
    }

    /**
     * @param \GdImage $image
     */
    public function getHeight($image): int
    {
        return imagesy($image);
    }

    /**
     * @param \GdImage $image
     */
    public function getWidth($image): int
    {
        return imagesx($image);
    }

    /**
     * @param \GdImage $image
     */
    public function crop($image, int $x, int $y, int $width, int $height, bool $immutable = false): \GdImage
    {
        $originalType = $this->getType($image);
        if (imageistruecolor($image)) {
            $newImage = imagecreatetruecolor($width, $height);
            if (!$newImage) {
                throw new GraphicLibraryException("Failed to create true color image");
            }
            imagealphablending($newImage, false);
            if ($originalType !== IMAGETYPE_JPEG) {
                imagesavealpha($newImage, true);
            }
        } else {
            $newImage = imagecreate($width, $height);
            if (!$newImage) {
                throw new GraphicLibraryException("Failed to create palette image");
            }
            imagepalettecopy($newImage, $image);
            $transparentIndex = imagecolortransparent($image);
            if ($transparentIndex >= 0) {
                $rgba = imagecolorsforindex($image, $transparentIndex);
                $newTransparentIndex = imagecolorresolve(
                    $newImage,
                    $rgba['red'],
                    $rgba['green'],
                    $rgba['blue']
                );
                imagecolortransparent($newImage, $newTransparentIndex);
                imagefill($newImage, 0, 0, $newTransparentIndex);
            }
        }
        if (!imagecopy($newImage, $image, 0, 0, $x, $y, $width, $height)) {
            throw new GraphicLibraryException("Failed to copy image");
        }
        if (!$immutable) {
            $this->close($image);
        }
        $this->mapType($newImage, $originalType);
        return $newImage;
    }

    /**
     * @param \GdImage $image
     */
    public function resize($image, int $width, int $height, bool $immutable = false): \GdImage
    {
        return $this->cropAndResize($image, 0, 0, imagesx($image), imagesy($image), $width, $height, $immutable);
    }

    /**
     * @param \GdImage $image
     */
    public function cropAndResize($image, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, bool $immutable = false): \GdImage
    {
        if (imageistruecolor($image)) {
            return $this->cropAndResizeTrueColor($image, $x, $y, $width, $height, $toWidth, $toHeight, $immutable);
        } else {
            return $this->cropAndResizeIndexedColor($image, $x, $y, $width, $height, $toWidth, $toHeight, $immutable);
        }
    }

    /**
     * @param \GdImage $image
     */
    private function cropAndResizeTrueColor(\GdImage $image, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, bool $immutable): \GdImage
    {
        $originalType = $this->getType($image);
        $newImage = imagecreatetruecolor($toWidth, $toHeight);
        if (!$newImage) {
            throw new GraphicLibraryException("Failed to create true color image");
        }
        $this->mapType($newImage, $originalType);

        imagealphablending($newImage, false);
        if ($originalType !== IMAGETYPE_JPEG) {
            imagesavealpha($newImage, true);
        }

        if (!imagecopyresampled($newImage, $image, 0, 0, $x, $y, $toWidth, $toHeight, $width, $height)) {
            throw new GraphicLibraryException("Failed to resample image");
        }

        if (!$immutable) {
            $this->close($image);
        }
        return $newImage;
    }

    /**
     * @param \GdImage $image
     */
    private function cropAndResizeIndexedColor(\GdImage $image, int $x, int $y, int $width, int $height, int $toWidth, int $toHeight, bool $immutable): \GdImage
    {
        $originalType = $this->getType($image);
        $newImage = imagecreatetruecolor($toWidth, $toHeight);
        if (!$newImage) {
            throw new GraphicLibraryException("Failed to create true color image");
        }
        $this->mapType($newImage, $originalType);

        $transparentIndex = imagecolortransparent($image);
        if ($transparentIndex >= 0) {
            $transparentRgb = imagecolorsforindex($image, $transparentIndex);
            $newTransparentIndex = imagecolorresolve($newImage, $transparentRgb['red'], $transparentRgb['green'], $transparentRgb['blue']);
            imagefilledrectangle($newImage, 0, 0, $toWidth, $toHeight, $newTransparentIndex);
            imagecolortransparent($newImage, $newTransparentIndex);
        }

        if (!imagecopyresampled($newImage, $image, 0, 0, $x, $y, $toWidth, $toHeight, $width, $height)) {
            throw new GraphicLibraryException("Failed to resample image");
        }

        $colorNumbers = imagecolorstotal($image);
        if (!imagetruecolortopalette($newImage, false, $colorNumbers)) {
            throw new GraphicLibraryException("Failed to convert image to palette");
        }

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