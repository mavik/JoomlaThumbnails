<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Thumbnails
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2024 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */

namespace Mavik\Thumbnails\Html;

use Mavik\Image\ImageFactory;

class Image
{
    /** @var \DOMElement */
    protected $domElement;

    /** ImageImmutable|ImageWithThumbnails */
    private $image;

    /** @var string */
    private $originalSrc;

    /** @var ImageFactory */
    protected $imageFactory;
    
    /** @var float */
    private $width;
    
    /** @var string */
    private $widthUnit;

    /** @var float */
    private $height;

    /** @var string */
    private $heightUnit;

    /** @var bool */
    private $isSizeInitialized = false;
    
    /** @var bool */
    private $isWidthInStyle = false;

    /** @var bool */
    private $isHeightInStyle = false;
    
    /** @var bool */
    private $isSizeInPixels = false;

    public function __construct(\DOMElement $domElement, ImageFactory $imageFactory)
    {
        if ($domElement->tagName !== 'img') {
            throw new \InvalidArgumentException('DOM element must be an <img> tag');
        }
        $this->domElement = $domElement;
        $this->imageFactory = $imageFactory;
        $this->originalSrc = $domElement->getAttribute('src');        
        $this->image = $imageFactory->createImmutable($this->originalSrc);
    }
    
    public function getSrc(): string
    {
        return $this->domElement->getAttribute('src');
    }

    public function getOriginalSrc(): string
    {
        return $this->originalSrc;
    }

    public function getWidth(): ?float
    {
        $this->initSize();
        return $this->width;
    }
    
    public function getHeight(): ?float
    {
        $this->initSize();
        return $this->height;
    }
    
    public function isSizeInPixels(): bool
    {
        $this->initSize();
        return $this->isSizeInPixels;
    }

    /**
     * Real width of the image in pixels
     */
    public function getRealWidth(): int
    {
        return $this->image->getWidth();
    }
    
    /**
     * Real height of the image in pixels
     */
    public function getRealHeight(): int
    {
        return $this->image->getHeight();
    }

    public function getAttribute(string $name): string
    {
        return $this->domElement->getAttribute($name);
    }
    
    public function hasAttribute(string $name): bool
    {
        return $this->domElement->hasAttribute($name);
    }

    public function getParentNode(): \DOMNode
    {
        return $this->domElement->parentNode;
    }

    public function useThumbnail(string $resizeType, array $thumbnailScales): bool
    {
        $this->image = $this->imageFactory->convertImageToImageWithThumbnails(
            $this->image,
            (int)$this->getWidth(),
            (int)$this->getHeight(),
            $resizeType,
            $thumbnailScales,
        );
        $thumbnails = $this->image->thumbnails();
        if (empty($thumbnails)) {
            return false;
        }
        $defaultThumbnail = $thumbnails[1] ?? current($thumbnails);
        $this->setSrc($defaultThumbnail->getUrl());
        $this->setSrcset($this->createSrcset($thumbnails));
        $this->setSize($defaultThumbnail->getWidth(), $defaultThumbnail->getHeight());
        return true;
    }

    public function setSize(?int $width, ?int $height): void
    {
        if ($this->isWidthInStyle()) {
            $style = $this->domElement->getAttribute('style');
            $widthStyle = $width ? 'width: ' . $width . 'px' : 'width: auto';
            $style = preg_replace('/(?<!\-)\bwidth\s*:\s*\d+\s*px/', $widthStyle, $style);
            $this->domElement->setAttribute('style', $style);
        } else {
            $this->domElement->setAttribute('width', (string)$width);
        }

        if ($this->isHeightInStyle()) {
            $style = $this->domElement->getAttribute('style');
            $heightStyle = $height ? 'height: ' . $height . 'px' : 'height: auto';
            $style = preg_replace('/(?<!\-)\bheight\s*:\s*\d+\s*px/', $heightStyle, $style);
            $this->domElement->setAttribute('style', $style);
        } else {
            $this->domElement->setAttribute('height', (string)$height);
        }

        $this->isSizeInitialized = false;
    }

    /**
     * @param ImageImmutable[] $thumbnails
     * @return string[]
     */
    private function createSrcset(array $thumbnails): array
    {
        $srcset = [];
        foreach ($thumbnails as $scale => $thumbnail) {
            $srcset[] = $thumbnail->getUrl() . " {$scale}x";
        }
        return $srcset;
    }
    
    private function isWidthInStyle(): bool
    {
        $this->initSize();
        return $this->isWidthInStyle;
    }
    
    private function isHeightInStyle(): bool
    {
        $this->initSize();
        return $this->isHeightInStyle;
    }

    private function setSrcset(array $srcset): void
    {
        $this->domElement->setAttribute('srcset', implode(', ', $srcset));
    }

    private function setSrc(string $src): void
    {
        $this->domElement->setAttribute('src', $src);
    }

    private function initSize(): void
    { 
        if ($this->isSizeInitialized) {
            return;
        }

        $this->isSizeInPixels = true;
        
        list($this->width, $this->widthUnit) = $this->numberValueFromAttribute('width');        
        list($this->height, $this->heightUnit) = $this->numberValueFromAttribute('height');
        
        if ($this->domElement->hasAttribute('style')) {
            $style = $this->domElement->getAttribute('style');
            list($widthInStyle, $widthUnitInStyle) = $this->numberValueFromStyle('width', $style);
            if ($widthInStyle && isset($widthUnitInStyle)) {
                $this->isWidthInStyle = true;
                $this->width = $widthInStyle;
                $this->widthUnit = $widthUnitInStyle;
            }
            list($heightInStyle, $heightUnitInStyle) = $this->numberValueFromStyle('height', $style);
            if (isset($heightInStyle) && isset($heightUnitInStyle)) {
                $this->isHeightInStyle = true;
                $this->height = $heightInStyle;
                $this->heightUnit = $heightUnitInStyle;
            }
        }
        
        $this->isSizeInPixels =
            (!isset($this->width) || $this->widthUnit == 'px')
            && (!isset($this->height) || $this->heightUnit == 'px')
        ;

        $this->isSizeInitialized = true;
    }
    
    /**
     * @return array [?float <value>, ?string <unit>]
     */
    private function numberValueFromAttribute(string $name): array
    {
        if (!$this->hasAttribute($name)) {
            return [null, null];
        }
        $attribute = $this->getAttribute($name);
        $numberReg = '\d+|\d*\.\d+';
        if (preg_match("/($numberReg)\s*(.*)/i", $attribute, $matches)) {
            $value = (float)$matches[1];
            $unit = strtolower(trim($matches[2])) ?: 'px';
        } else {
            $value = null;
            $unit = null;
        }
        return [
            $value,
            $unit,
        ];
    }
    
    /**
     * @return array [?float <value>, ?string <unit>]
     */
    private function numberValueFromStyle(string $property, string $style): array
    {
        $numberReg = '\d+|\d*\.\d+';
        if (preg_match("/\b{$property}\s*:\s*($numberReg)\s*([a-zA-Z\%]+)/si", $style, $matches)) {
            $value = (float)$matches[1];
            $unit = strtolower(trim($matches[2]));            
        } else {
            $value = null;
            $unit = null;
        }        
        return [
            $value,
            $unit,
        ];
    }
}
