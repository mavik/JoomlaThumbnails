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

class ImageSize
{
    /** @var int|null **/
    public $width;
    
    /** @var int|null **/
    public $height;    
    
    public function __construct(?int $width = null, ?int $height = null)
    {
        if (empty($width) && empty($height)) {
            throw new Exception('At least one parameter of ImageSize constructor has to be not null.');
        }
        if ($width < 0 || $height < 0) {
            throw new Exception('Width and height cannot be less than zero.');
        }
        $this->width = $width;
        $this->height = $height;
    }
    
    public function scale(float $scale): self
    {
        return new self(
            max((int)round($this->width * $scale), 1),
            max((int)round($this->height * $scale), 1)
        );
    }
    
    public function lessThan(self $size): bool
    {
        return
            $this->width &&
            $size->width &&
            $this->height && 
            $size->height &&
            $this->width < $size->width &&
            $this->height < $size->height
        ;
    }
}
