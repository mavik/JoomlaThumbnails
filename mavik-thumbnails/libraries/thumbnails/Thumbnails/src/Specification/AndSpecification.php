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

class AndSpecification extends AbstractSpecification
{
    private $specification1;
    private $specification2;

    public function __construct(AbstractSpecification $specification1, AbstractSpecification $specification2)
    {
        $this->specification1 = $specification1;
        $this->specification2 = $specification2;
    }

    public function isSatisfiedBy($candidate): bool
    {
        return
            $this->specification1->isSatisfiedBy($candidate)
            && $this->specification2->isSatisfiedBy($candidate)
        ;
    }
}