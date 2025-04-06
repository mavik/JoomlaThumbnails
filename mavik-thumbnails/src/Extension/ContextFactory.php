<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

defined('_JEXEC') or die;

class ContextFactory
{
    public function createContext($contextName): ?ContextInterface
    {
        $normalizedtName = str_replace('_', '', ucwords($contextName, '._'));
        $filePath = __DIR__ . '/Context/' . str_replace('.', '/', $normalizedtName) . '.php';
        if (file_exists($filePath)) {
            $className = 'Mavik\\Plugin\\Content\\Thumbnails\\Extension\\Context\\' . str_replace('.', '\\', $normalizedtName);
            return new $className();
        }
        return null;
    }
}
