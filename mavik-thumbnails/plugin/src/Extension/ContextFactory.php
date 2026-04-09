<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

use Mavik\Plugin\Content\Thumbnails\Extension\Context\BaseContext;
use stdClass;

defined('_JEXEC') or die;

use Mavik\Thumbnails\Configuration;
use Mavik\Plugin\Content\Thumbnails\Extension\Context\ContextInterface;

class ContextFactory
{
    public function __construct(private Configuration $configuration)
    {
    }

    public function createContext(string $contextName, stdClass $item): ContextInterface
    {
        $normalizedtName = str_replace(['_', '.'], ['', '\\'], ucwords($contextName, '._'));
        $className = 'Mavik\\Plugin\\Content\\Thumbnails\\Extension\\Context\\' . $normalizedtName;
        if (class_exists($className)) {
            return new $className($item, $this->configuration);
        } else {
            return new BaseContext($item, $this->configuration);
        }
    }
}
