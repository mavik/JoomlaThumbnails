<?php
declare(strict_types=1);

namespace Mavik\Thumbnails\Action;

use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Specification\AbstractSpecification;

abstract class AbstractAction implements ActionInterface
{
    private AbstractSpecification $specification;

    public function __construct(protected Configuration $configuration)
    {
    }

    public function specification(): AbstractSpecification
    {
        if (empty($this->specification)) {
            $this->specification = $this->createSpecification();
        }
        return $this->specification;
    }

    abstract protected function createSpecification(): AbstractSpecification;
}