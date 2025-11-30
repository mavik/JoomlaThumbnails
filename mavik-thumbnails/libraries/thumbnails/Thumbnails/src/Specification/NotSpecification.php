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

class NotSpecification extends Specification
{
    private $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function isSatisfiedBy($candidate): bool
    {
        return !$this->specification->isSatisfiedBy($candidate);
    }
}