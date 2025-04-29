<?php

namespace Mavik\Plugin\Content\Thumbnails\Extension;

defined('_JEXEC') or die;

use Mavik\Thumbnails\Configuration;
use Mavik\Thumbnails\Configuration\Base as ConfBase;
use Mavik\Thumbnails\Configuration\Server as ConfServer;
use Joomla\Registry\Registry;
use Joomla\CMS\Uri\Uri;

class ConfigurationFactory
{
    public function create(Registry $params): Configuration
    {
        $confServer = new ConfServer(
            Uri::base(),
            JPATH_ROOT,
            $params->get('thumbnailsDir', 'images/thumbnails'),
        );
        $confBase = new ConfBase(
            $params->get('resizeMethod', 'fit'),
            array_map('intval', $params->get('adaptiveScales', [1,2,3])),
            $this->stringToArray($params->get('includeClasses', '')),
            $this->stringToArray($params->get('excludeClasses', '')),
            $params->get('insideLinkAction', ConfBase::USE_DEFAULT_SIZE_NO),
            $params->get('useDefaultSize', ConfBase::USE_DEFAULT_SIZE_NO),
            $params->get('defaultWidth', null),
            $params->get('defaultHeight', null),
        );
        return new Configuration($confServer, $confBase);        
    }

    private function stringToArray(string $string): array
    {
        return array_map('trim', explode(',', $string));
    }
}