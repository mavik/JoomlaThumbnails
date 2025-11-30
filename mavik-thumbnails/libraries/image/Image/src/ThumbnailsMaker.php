<?php

declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2022 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */

namespace Mavik\Image;

use Mavik\Image\ThumbnailsMaker\ResizeStrategyInterface;

/**
 * Create thumbnails for image
 */
class ThumbnailsMaker
{
    private Configuration $configuration;

    public function __construct(
        Configuration $configuration,
    ) {
        $this->configuration = $configuration;
    }

    /**
     * Thumbnails for $originalSrc
     * 
     * You can create a few thumbnails in different scales from one image using parameter "scales".
     * For example, if scales = [1,2], it will be created 2 thumbnails,
     * in size width x height and 2*width x 2*height.
     * 
     * Please note, if requested thumbnail has the same or bigger width or height
     * than original image, it won't be created.
     * 
     * @param ImageImmutable|Image $image Can be object of class ImageImmutable or Image, but ImageImmutable is recommended.
     * @return ImageImmutable[] As indexes are used the scales.
     */
    public function thumbnails(
        Image $image,
        ImageSize $thumbnailSize,
        ResizeStrategyInterface $resizeStrategy,
        array $scales = [1],
    ): array {
        $thumbnails = [];
        foreach ($scales as $scale) {
            $thumbnail = $this->thumbnailForScale($image, $thumbnailSize, $resizeStrategy, $scale);
            if ($thumbnail) {
                $thumbnails[$scale] = $thumbnail;
            }
        }
        return $thumbnails;
    }

    private function thumbnailForScale(
        Image $image,
        ImageSize $thumbnailSize,
        ResizeStrategyInterface $resizeStrategy,
        float $scale
    ): ?ImageImmutable {
        $originalSize = $image->getSize();
        $scaledThumbnailSize = $thumbnailSize->scale($scale);
        if (!$scaledThumbnailSize->lessThan($originalSize)) {
            return null;
        }
        $thumbnailPath = $this->thumbnailPath(
            $image,
            $scaledThumbnailSize,
            $resizeStrategy->name(),
        );
        if (file_exists($thumbnailPath) && filemtime($thumbnailPath) >= filemtime($image->getPath())) {
            return ImageImmutable::create($thumbnailPath, $this->configuration);
        }
        return $this->createThumbnailForScale(
            $image,
            $thumbnailSize,
            $resizeStrategy,
            $thumbnailPath,
            $scale
        );
    }

    private function createThumbnailForScale(
        Image $image,
        ImageSize $thumbnailSize,
        ResizeStrategyInterface $resizeStrategy,
        string $filePath,
        float $scale
    ): ?ImageImmutable {
        $originalSize = $image->getSize();
        $scaledThumbnailSize = $thumbnailSize->scale($scale);
        if (!$scaledThumbnailSize->lessThan($originalSize)) {
            return null;
        }
        $originalImageArea = $resizeStrategy->originalImageArea($originalSize, $scaledThumbnailSize);
        $realThumbnailSize = $resizeStrategy->realThumbnailSize($originalSize, $scaledThumbnailSize);
        $thumbnail = ($image instanceof ImageImmutable ? $image : clone $image)
            ->cropAndResize(
                $originalImageArea->x,
                $originalImageArea->y,
                $originalImageArea->width,
                $originalImageArea->height,
                $realThumbnailSize->width,
                $realThumbnailSize->height
            );
        $thumbnail->save($filePath);
        return $thumbnail;
    }

    private function thumbnailPath(
        Image $image,
        ImageSize $scaledThumbnailSize,
        string $resizeStrategyName,
    ): string {
        $imagePath = $image->getPath();
        if ($imagePath && strpos($imagePath, $this->configuration->webRootDirectory()) === 0) {
            $imagePath = substr($imagePath, strlen($this->configuration->webRootDirectory()));
        } else {
            $imagePath = preg_replace('/^\w+\:\/\//', '', $imagePath);;
        }
        $lastDotPosition = strrpos($imagePath, '.') ?: strlen($imagePath);
        return
            $this->configuration->thumbnailsDirectory()
            . substr($imagePath, 0, $lastDotPosition)
            . '-' . $resizeStrategyName
            . '-' . $scaledThumbnailSize->width . 'x' . $scaledThumbnailSize->height
            . '.' . substr($imagePath, $lastDotPosition + 1);
    }
}
