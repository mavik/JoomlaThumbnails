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
namespace Mavik\Thumbnails;

use Mavik\Thumbnails\JsAndCss;

class Result
{
    /** @var string */
    public $html;
    
    /** @var JsAndCss */
    public $jsAndCss;
    
    public function __construct(string $html, JsAndCss $jsAndCss)
    {
        $this->html = $html;
        $this->jsAndCss = $jsAndCss;
    }
}
