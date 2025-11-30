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

namespace Mavik\Thumbnails\Action;

use Mavik\Thumbnails\Html\Image as Image;
use Mavik\Thumbnails\JsAndCss;

interface ActionInterface
{
    /**
     * Change $image and add JS and CSS to $jsAndCss.
     */
    public function __invoke(Image $image, JsAndCss $jsAndCss): void;
}
