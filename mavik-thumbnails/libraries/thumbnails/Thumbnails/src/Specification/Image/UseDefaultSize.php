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
namespace Mavik\Thumbnails\Specification\Image;

use Mavik\Thumbnails\Html\Image as HtmlImage;
use Mavik\Thumbnails\Configuration;

class UseDefaultSize extends Image
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    protected function isSatisfiedByImage(HtmlImage $image): bool
    {
        switch ($this->configuration->base()->useDefaultSize()) {
            case Configuration\Base::USE_DEFAULT_SIZE_NO:
                return false;
            case Configuration\Base::USE_DEFAULT_SIZE_ALL;
                return true;
            case Configuration\Base::USE_DEFAULT_SIZE_WITHOUT_SIZE:
                if ($image->getWidth() || $image->getHeight()) {
                    return false;
                }
                return true;
        }
        throw new \LogicException('Unknown value for useDefaultSize: ' . $this->configuration->base()->useDefaultSize());
    }
}
