<?php
declare(strict_types=1);

namespace Mavik\Plugin\Content\Thumbnails\Extension\Context\ComContent\Specification;

use Mavik\Thumbnails\Specification\AbstractSpecification;
use Mavik\Thumbnails\Configuration;

class Always extends AbstractSpecification
{
    public function __construct(
        private Configuration $configuration
    ) {
    }

    /**
     * @param mixed $candidate
     */
    public function isSatisfiedBy($candidate): bool
    {
        return true;
    }
}
