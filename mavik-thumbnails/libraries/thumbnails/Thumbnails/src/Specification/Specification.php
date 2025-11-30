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
namespace Mavik\Thumbnails\Specification;

abstract class Specification
{
    public abstract function isSatisfiedBy($candidate): bool;
    
    public function and(Specification $specification): Specification
    {
        return new AndSpecification($this, $specification);
    }

    public function or(Specification $specification): Specification
    {
        return new OrSpecification($this, $specification);
    }

    public function not(): Specification
    {
        return new NotSpecification($this);
    }
}