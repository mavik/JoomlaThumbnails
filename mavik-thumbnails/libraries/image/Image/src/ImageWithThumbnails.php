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

use Mavik\Image\ThumbnailsMaker;
use Mavik\Image\ThumbnailsMaker\ResizeStrategyInterface;

class ImageWithThumbnails extends ImageImmutable
{
    /** @var ImageImmutable[] */
    private $thumbnails = [];

    /** @var ImageSize */
    private $thumbnailSize;

    /** @var ResizeStrategyInterface */
    private $resizeStrategy;

    /** @var int[] */
    private $thumbnailScails;

    /** @var ThumbnailsMaker */
    private $thumbnailsMaker;

    public static function create(
        string $src,
        Configuration $configuration,
        ImageSize $thumbnailSize = null,
        ResizeStrategyInterface $resizeStrategy = null,
        ThumbnailsMaker $thumbnailsMaker = null,
        array $thumbnailScails = [1],
    ): static {
        $image = parent::create($src, $configuration);
        $image->thumbnailSize = $thumbnailSize;
        $image->resizeStrategy = $resizeStrategy;
        $image->thumbnailsMaker = $thumbnailsMaker;
        $image->thumbnailScails = $thumbnailScails;
        return $image;
    }

    public static function createFromString(
        string $content,
        Configuration $configuration,
        ImageSize $thumbnailSize = null,
        ResizeStrategyInterface $resizeStrategy = null,
        ThumbnailsMaker $thumbnailsMaker = null,
        array $thumbnailScails = [1],
    ): static {
        $image = parent::createFromString($content, $configuration);
        $image->thumbnailSize = $thumbnailSize;
        $image->resizeStrategy = $resizeStrategy;
        $image->thumbnailsMaker = $thumbnailsMaker;
        $image->thumbnailScails = $thumbnailScails;
        return $image;
    }

    /**
     * Create an instance from the Image and destroy the Image
     */
    public static function convertImage(
        Image $image,
        Configuration $configuration,
        ImageSize $thumbnailSize = null,
        ResizeStrategyInterface $resizeStrategy = null,
        ThumbnailsMaker $thumbnailsMaker = null,
        array $thumbnailScails = [1],
    ): static {
        $imageWithThumbnails = new static($configuration);
        $imageWithThumbnails->resource = $image->resource;
        $imageWithThumbnails->type = $image->type;
        $imageWithThumbnails->size = $image->size;
        $imageWithThumbnails->file = $image->file;
        $imageWithThumbnails->graphicLibrary = $image->graphicLibrary;
        $imageWithThumbnails->thumbnailSize = $thumbnailSize;
        $imageWithThumbnails->resizeStrategy = $resizeStrategy;
        $imageWithThumbnails->thumbnailsMaker = $thumbnailsMaker;
        $imageWithThumbnails->thumbnailScails = $thumbnailScails;
        unset($image);
        return $imageWithThumbnails;
    }

    /**
     * @return ImageImmutable[]
     */
    public function thumbnails(): array 
    {
        if (!isset($this->thumbnails)) {
            if (
                isset($this->thumbnailSize)
                && isset($this->resizeStrategy)
                && isset($this->thumbnailsMaker)
                && !empty($this->thumbnailScails)
            ) {
                $this->thumbnails = $this->thumbnailsMaker->thumbnails(
                    $this,
                    $this->thumbnailSize,
                    $this->resizeStrategy,
                    $this->thumbnailScails
                );
            } else {
                $this->thumbnails = [];
            }  
        }
        return $this->thumbnails;
    }
}
