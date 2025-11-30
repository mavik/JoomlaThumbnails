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

use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Html\Image as HtmlImage;

class HasSuitedClass extends Image
{
    private Configuration $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    protected function isSatisfiedByImage (HtmlImage $image): bool
    {
        $classAtr = $image->getAttribute('class');
        preg_match_all('/(\w+)/', $classAtr, $matches, PREG_PATTERN_ORDER);
        $classes = $matches[1];
        $includeClasses = $this->configuration->base()->includeClasses();
        $excludeClasses = $this->configuration->base()->excludeClasses();
        return
            (empty($includeClasses) || !empty(array_intersect($classes, $includeClasses)))
            && (empty($excludeClasses) || empty(array_intersect($classes, $excludeClasses)))
        ;
    }
}
