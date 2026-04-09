<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

use Mavik\Plugin\Content\Thumbnails\Extension\Context\BaseContext;

defined('_JEXEC') or die;

use Mavik\Thumbnails\Configuration;
use Mavik\Plugin\Content\Thumbnails\Extension\Context\ContextInterface;

class ContextFactory
{
    public function __construct(private Configuration $configuration)
    {
    }

    public function createContext($contextName): ContextInterface
    {
        $normalizedtName = str_replace(['_', '.'], ['', '\\'], ucwords($contextName, '._'));
        $className = 'Mavik\\Plugin\\Content\\Thumbnails\\Extension\\Context\\' . $normalizedtName;
        if (class_exists($className)) {
            return new $className($this->configuration);
        } else {
            return new BaseContext($this->configuration);
        }
    }
}
