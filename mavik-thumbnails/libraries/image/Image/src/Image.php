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

class Image
{
    /** @var mixed */
    protected $resource;

    /** @var int constant IMAGETYPE_XXX */
    protected $type;

    /** @var ImageSize **/
    protected $size;

    /** @var ImageFile */
    protected $file;

    /** @var Configuration */
    protected $configuration;

    /** @var GraphicLibraryInterface */
    protected $graphicLibrary;

    final protected function __construct(
        Configuration $configuration
    ) {
        $this->graphicLibrary = $configuration->graphicLibrary();
        $this->configuration = $configuration;
    }

    /**
     * Create an instance from the file
     * 
     * @param string $src Path or URL
     */
    public static function create(string $src, Configuration $configuration): static
    {
        $fileName = new FileName($src, $configuration->baseUri(), $configuration->webRootDirectory());
        $imageFile = new ImageFile($fileName);
        $image = new static($configuration);
        $image->file = $imageFile;
        return $image;
    }

    /**
     * Create an instance from the string
     */
    public static function createFromString(string $content, Configuration $configuration): static
    {
        $image = new static($configuration);
        $image->resource = $configuration->graphicLibrary()->loadFromString($content);
        $info = getimagesizefromstring($content);
        $image->type = $info[2];
        return $image;
    }

    public function save(string $path): static
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }
        $this->graphicLibrary->save($this->getResource(), $path, $this->getType());
        $this->file = new FileName($path, $this->configuration->baseUri(), $this->configuration->webRootDirectory());
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->file ? $this->file->getUrl() : null;
    }

    public function getPath(): ?string
    {
        return $this->file ? $this->file->getPath() : null;
    }

    /**
     * @return int|null IMAGETYPE_XXX
     */
    public function getType(): int
    {
        if (!isset($this->type)) {
            $this->type = $this->file->getType();
        }
        return $this->type;
    }

    public function getSize(): ImageSize
    {
        if (!isset($this->size)) {
            // Resource is not creating in constructor
            if (isset($this->resource)) {
                $this->size = $this->getImageSizeFromResource();
            } elseif (isset($this->file)) {
                $this->size = $this->file->getImageSize();
            } else {
                throw new \LogicException();
            }
        }
        return $this->size;
    }

    /**
     * Alias for getSize()->width
     */
    public function getWidth(): int
    {
        return $this->getSize()->width;
    }

    /**
     * Alias for getSize()->height
     */
    public function getHeight(): int
    {
        return $this->getSize()->height;
    }

    private function getImageSizeFromResource(): ImageSize
    {
        return new ImageSize(
            $this->graphicLibrary->getWidth($this->resource),
            $this->graphicLibrary->getHeight($this->resource)
        );
    }

    public function getFileSize(): ?int
    {
        return $this->file ? $this->file->getFileSize() : null;
    }

    public function resize(int $width, int $height): Image
    {
        $this->resource = $this->graphicLibrary->resize($this->getResource(), $width, $height);
        $this->resetSize();
        return $this;
    }

    public function crop(int $x, int $y, int $width, int $height): Image
    {
        $this->resource = $this->graphicLibrary->crop($this->getResource(), $x, $y, $width, $height);
        $this->resetSize();
        return $this;
    }

    public function cropAndResize(
        int $x,
        int $y,
        int $width,
        int $height,
        int $toWidth,
        int $toHeight
    ) {
        $this->resource = $this->graphicLibrary->cropAndResize($this->getResource(), $x, $y, $width, $height, $toWidth, $toHeight);
        $this->resetSize();
        return $this;
    }

    /**
     * @return mixed Depends on graphic library
     */
    protected function getResource()
    {
        if (!isset($this->resource)) {
            $this->resource = $this->graphicLibrary->load($this->file);
        }
        return $this->resource;
    }

    /**
     * Unset width and height
     */
    protected function resetSize(): void
    {
        $this->size = null;
    }

    public function __clone()
    {
        if (isset($this->file)) {
            $this->file = clone $this->file;
        }
        if (isset($this->graphicLibrary)) {
            $this->graphicLibrary = clone $this->graphicLibrary;
        }
        if (isset($this->resource)) {
            $this->resource = $this->graphicLibrary->clone($this->resource);
        }
    }
}