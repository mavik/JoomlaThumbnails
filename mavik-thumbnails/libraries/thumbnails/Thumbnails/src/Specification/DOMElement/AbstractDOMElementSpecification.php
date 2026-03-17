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
namespace Mavik\Thumbnails\Specification\DOMElement;

use Mavik\Thumbnails\Specification\AbstractSpecification;

abstract class AbstractDOMElementSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($candidate): bool
    {
        if ($candidate instanceof \DOMElement) {
            return $this->isSatisfiedByDOMElement($candidate);
        }
        throw new \InvalidArgumentException('Expected instance of \DOMElement');
    }

    abstract protected function isSatisfiedByDOMElement(\DOMElement $element): bool;
}
