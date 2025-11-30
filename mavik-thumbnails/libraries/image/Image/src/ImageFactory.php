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

    public function create(string $src): Image
    {
        return Image::create($src, $this->configuration);
    }
    
    public function createFromString(string $src): Image
    {
        return Image::createFromString($src, $this->configuration);
    }
    
    public function createImmutable(string $src): ImageImmutable
    {
        return ImageImmutable::create($src, $this->configuration);
    }
    
    public function createImmutableFromString(string $src): ImageImmutable
    {
        return ImageImmutable::createFromString($src, $this->configuration);
    }
    
    public function createImageWithThumbnails(
        string $src,
        int $thumbnailWidth = null,
        int $thumbnailHeight = null,
        string $resizeType = 'stretch',
        array $thumbnailScales = [1]
    ): ImageWithThumbnails {
        return ImageWithThumbnails::create(
            $src,
            $this->configuration,
            new ImageSize($thumbnailWidth, $thumbnailHeight),
            ResizeStrategyFactory::create($resizeType),
            $this->thumbnailsMaker(),
            $thumbnailScales,
        );
    }
    
    public function createImageWithThumbnailsFromString(
        string $content,
        int $thumbnailWidth = null,
        int $thumbnailHeight = null,
        string $resizeType = 'stretch',
        array $thumbnailScales = [1]
    ): ImageWithThumbnails {
        return ImageWithThumbnails::createFromString(
            $content,
            $this->configuration,
            new ImageSize($thumbnailWidth, $thumbnailHeight),
            ResizeStrategyFactory::create($resizeType),
            $this->thumbnailsMaker(),
            $thumbnailScales,
        );
    }

    public function convertImageToImageWithThumbnails(
        Image $image,
        int $thumbWidth,
        int $thumbHeight,
        string $resizeType ='stretch',
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

    private function thumbnailsMaker(): ThumbnailsMaker
    {
        if (!isset($this->thumbnailsMaker)) {
            $this->thumbnailsMaker = new ThumbnailsMaker($this->configuration);
        }
        return $this->thumbnailsMaker;
    }
}
