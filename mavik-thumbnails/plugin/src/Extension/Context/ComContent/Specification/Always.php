<?php
declare(strict_types=1);

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context\Blog\Specification;

use Mavik\Thumbnails\Specification\AbstractSpecification;

class Always extends AbstractSpecification
{
    /**
     * @param mixed $candidate
     */
    public function isSatisfiedBy($candidate): bool
    {
        return true;
    }
}
