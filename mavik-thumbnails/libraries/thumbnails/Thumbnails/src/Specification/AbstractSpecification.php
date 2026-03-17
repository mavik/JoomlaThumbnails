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

abstract class AbstractSpecification
{
    public abstract function isSatisfiedBy($candidate): bool;

    public function and(AbstractSpecification $specification): AbstractSpecification
    {
        return new AndSpecification($this, $specification);
    }

    public function or(AbstractSpecification $specification): AbstractSpecification
    {
        return new OrSpecification($this, $specification);
    }

    public function not(): AbstractSpecification
    {
        return new NotSpecification($this);
    }
}