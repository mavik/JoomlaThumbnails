<?php
declare(strict_types=1);

/*
 *  PHP Library for Image processing and creating thumbnails
 *  
 *  @package Mavik\Image
 *  @author Vitalii Marenkov <admin@mavik.com.ua>
 *  @copyright 2021 Vitalii Marenkov
 *  @license GNU General Public License version 2 or later; see LICENSE
 */
namespace Mavik\Thumbnails\Configuration;

class Base
{
    const INSIDE_LINK_ACTION_NONE = 'none';
    const INSIDE_LINK_ACTION_RESIZE = 'resize';
    const INSIDE_LINK_ACTION_ALL = 'all';

    const USE_DEFAULT_SIZE_NO = 'no';
    const USE_DEFAULT_SIZE_WITHOUT_SIZE = 'without_size';
    const USE_DEFAULT_SIZE_ALL = 'all';
    
    /** @var string */
    private $resizeType;
    
    /** @var float[] */
    private $scales;
    
    /** @var string[] */
    private $includeClasses;
    
    /** @var string[] */
    private $excludeClasses;
    
    /** @var string */
    private $insideLinkAction;

    /** @var string */
    private $useDefaultSize;

    /** @var int|null */
    private $defaultWidth;

    /** @var int|null */
    private $defaultHeight;

    /** @var string */
    private $popUp;

    public function __construct(
        string $resizeType = 'Stretch',
        array $scales = [1],
        array $includeClasses = [],
        array $excludeClasses = [],
        string $insideLinkAction = self::INSIDE_LINK_ACTION_NONE,
        string $useDefaultSize = self::USE_DEFAULT_SIZE_NO,
        int $defaultWidth = null,
        int $defaultHeight = null,
        string $popUp = null,
    ) {
        $this->resizeType = $resizeType;
        $this->scales = $scales;
        $this->includeClasses = $includeClasses;
        $this->excludeClasses = $excludeClasses;
        $this->insideLinkAction = $insideLinkAction;
        $this->useDefaultSize = $useDefaultSize;
        $this->defaultWidth = $defaultWidth;
        $this->defaultHeight = $defaultHeight;
        $this->popUp = $popUp;
    }

    public function resizeType(): string
    {
        return $this->resizeType;
    }

    /** @return float[] */
    public function scales(): array
    {
        return $this->scales;
    }
    
    /** @return string[] */
    public function includeClasses(): array
    {
        return $this->includeClasses;
    }

    /** @return string[] */
    public function excludeClasses(): array
    {
        return $this->excludeClasses;
    }
    
    public function insideLinkAction(): string
    {
        return $this->insideLinkAction;
    }

    public function useDefaultSize(): string
    {
        return $this->useDefaultSize;
    }

    public function defaultWidth(): ?int
    {
        return $this->defaultWidth;
    }

    public function defaultHeight(): ?int
    {
        return $this->defaultHeight;
    }

    public function popUp(): ?string
    {
        return $this->popUp;
    }
}
