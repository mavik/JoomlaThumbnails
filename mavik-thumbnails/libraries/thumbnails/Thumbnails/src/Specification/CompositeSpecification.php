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

abstract class CompositeSpecification extends Specification
{
    protected Specification $innerSpec;

    public function __construct(Specification $spec)
    {
        $this->innerSpec = $spec;
    }

    public function isSatisfiedBy($candidate): bool
    {
        return $this->innerSpec->isSatisfiedBy($candidate);
    }
}
