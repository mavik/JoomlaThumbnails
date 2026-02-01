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

use Mavik\Image\ThumbnailsMaker\ResizeStrategyFactory;

/**
 * Facade of the library
 */
class ImageFactory
{
    /** @var Configuration */
    private $configuration;

    /** @var ThumbnailsMaker */
    private $thumbnailsMaker;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Create mutable image from file
     *
     * @param string $source URL or path to file
     */
    public function create(string $source): Image
    {
        return Image::create($source, $this->configuration);
    }

    /**
     * Create mutable image from string content
     *
     * @param string $string String content of the image
     */
    public function createFromString(string $string): Image
    {
        return Image::createFromString($string, $this->configuration);
    }

    /**
     * Create immutable image from file
     * 
     * @param string $source URL or path to file
     */
    public function createImmutable(string $source): ImageImmutable
    {
        return ImageImmutable::create($source, $this->configuration);
    }

    /**
     * Create immutable image from string content
     * 
     * @param string $string String content of the image
     */
    public function createImmutableFromString(string $string): ImageImmutable
    {
        return ImageImmutable::createFromString($string, $this->configuration);
    }

    /**
     * Create image with thumbnails from file
     *
     * @param string $source URL or path to file
     * @param int|null $thumbnailWidth Thumbnail width
     * @param int|null $thumbnailHeight Thumbnail height
     * @param string $resizeType Resize type
     * @param int[] $thumbnailScales Thumbnail scales
     */
    public function createImageWithThumbnails(
        string $source,
        ?int $thumbnailWidth = null,
        ?int $thumbnailHeight = null,
        string $resizeType = 'stretch',
        array $thumbnailScales = [1]
    ): ImageWithThumbnails {
        return ImageWithThumbnails::create(
            $source,
            $this->configuration,
            new ImageSize($thumbnailWidth, $thumbnailHeight),
            ResizeStrategyFactory::create($resizeType),
            $this->thumbnailsMaker(),
            $thumbnailScales,
        );
    }

    /**
     * Create image with thumbnails from string content
     *
     * @param string $string String content of the image
     * @param int|null $thumbnailWidth Thumbnail width
     * @param int|null $thumbnailHeight Thumbnail height
     * @param string $resizeType Resize type
     * @param int[] $thumbnailScales Thumbnail scales
     */
    public function createImageWithThumbnailsFromString(
        string $string,
        ?int $thumbnailWidth = null,
        ?int $thumbnailHeight = null,
        string $resizeType = 'stretch',
        array $thumbnailScales = [1]
    ): ImageWithThumbnails {
        return ImageWithThumbnails::createFromString(
            $string,
            $this->configuration,
            new ImageSize($thumbnailWidth, $thumbnailHeight),
            ResizeStrategyFactory::create($resizeType),
            $this->thumbnailsMaker(),
            $thumbnailScales,
        );
    }

    /**
     * Convert existing Image object to ImageWithThumbnails
     *
     * @param Image $image Image object
     * @param int $thumbWidth Thumbnail width
     * @param int $thumbHeight Thumbnail height
     * @param string $resizeType Resize type
     * @param int[] $thumbnailScales Thumbnail scales
     */
    public function convertImageToImageWithThumbnails(
        Image $image,
        int $thumbWidth,
        int $thumbHeight,
        string $resizeType = 'stretch',
        array $thumbnailScales = [1]
    ): ImageWithThumbnails {
        return ImageWithThumbnails::convertImage(
            $image,
            $this->configuration,
            new ImageSize($thumbWidth, $thumbHeight),
            ResizeStrategyFactory::create($resizeType),
            $this->thumbnailsMaker(),
            $thumbnailScales,
        );
    }

    /**
     * Get or create ThumbnailsMaker instance
     */
    private function thumbnailsMaker(): ThumbnailsMaker
    {
        if (!isset($this->thumbnailsMaker)) {
            $this->thumbnailsMaker = new ThumbnailsMaker($this->configuration);
        }
        return $this->thumbnailsMaker;
    }
}
